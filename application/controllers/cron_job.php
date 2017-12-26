<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/xmlrpc.inc';
require APPPATH . '/libraries/xmlrpcs.inc';
require APPPATH . '/libraries/ccave/Payment.php';
require APPPATH . '/libraries/NetworkAPI.php';

use Kishanio\CCAvenue\Payment as CCAvenueClient;

class Cron_job extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('transaction');
        $this->load->model('cronJob');
        $this->load->model('tbl_plans');
    }

    public function index()
    {
        $request_result = $this->cronJob->getAllTransaction();

        foreach ($request_result as $item) {

            $status = $item->status;
            if ($status == 'new') {
                $expiry_date = $item->expire;
                $start = strtotime($expiry_date);
                $end = strtotime(date("Y-m-d h:i:s"));

                if ($start < $end) {
                    $order_id = $item->transaction_id;
                    $data = array(
                        'column' => 'id',
                        'value' => $order_id,
                    );
                    $request_result = $this->transaction->getTranslationData($data);
                    if (count($request_result) > 0) {
                        $network = new NetworkAPI();
                        $request_arr = json_decode($request_result[0]->request_json, true);
                        $subsno = $request_arr['subsno'];
                        $actid = $request_result[0]->actid;
                        $cpnTypeId = $request_arr['cpnTypeId'];
                        $domid = $request_arr['domid'];
                        $date = date("Ymd\TH:i:s", time());
                        $network_res = $network->rechargeSubscriptionViaCouponType($subsno, $cpnTypeId, $domid);
                        if (!is_array($network_res)) {
                            $data = array(
                                'tran_response' => json_encode($network_res),
                            );
                            $resFlag = $this->transaction->editTranslation($data, $order_id);

                            $dataCron = array(
                                'status' => 'old',
                            );
                            $resFlag = $this->cronJob->editTranslation($dataCron, $item->id);
                            $message = "Dear " . $actid . " Your account is renewed in the broadband plan of " . $cpnTypeId;
                            $query = array(
                                'mobile' => '9833268386',
                                //'mobile' => $mobile,
                                'message' => $message
                            );
                            $data = sendSms($query);
                        } else {
                            $this->global['error'] = $network_res['faultString'];
                        }
                    }
                }
            }


        }
    }

    function httpGet()
    {
        $query = array(
            'mobile' => '8866224093',
            'message' => 'Test'
        );
        $data = sendSms($query);
        var_dump($data);
    }

}

?>