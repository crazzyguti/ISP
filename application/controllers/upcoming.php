<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/xmlrpc.inc';
require APPPATH . '/libraries/xmlrpcs.inc';
require APPPATH . '/libraries/ccave/Payment.php';
require APPPATH . '/libraries/NetworkAPI.php';

class Upcoming extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('transaction');
        $this->load->model('cronJob');
        $this->load->model('tbl_plans');
        $this->isLoggedIn();
        if(!$this->isAdmin() || !$this->isManager())
        {
            redirect('user');
        }
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';
        $this->loadViews("complaint", $this->global, NULL, NULL);
    }

    public function pending()
    {
        $this->global['pageTitle'] = 'CodeInsect : Pending';
        $this->loadViews("pending", $this->global, NULL, NULL);
    }

    public function listData()
    {
        $this->global['pageTitle'] = 'CodeInsect : upcoming';
        $this->global['upcomingData'] = $this->user_model->upcomingData();
        $this->loadViews("upcoming", $this->global, NULL, NULL);
    }

    public function activated()
    {
        $this->global['pageTitle'] = 'CodeInsect : Activated';

        $this->loadViews("activated", $this->global, NULL, NULL);
    }

    public function immediately()
    {
        $this->global['pageTitle'] = 'CodeInsect : Upcoming';
        if ($_GET['id'] != '') {
            $id = $_GET['id'];
            $con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");
            //$con = mysqli_connect("localhost", "root", "", "cias");
            $plan_query = mysqli_query($con, "SELECT * FROM cronJob WHERE id=$id");
            $request_result = mysqli_fetch_assoc($plan_query);
            if (count($request_result) > 0) {
                if ($request_result['status'] == 'new') {
                    $expiry_date = $request_result['expire'];
                    $start = strtotime($expiry_date);
                    $end = strtotime(date("Y-m-d h:i:s"));
                    if ($start < $end) {
                        $order_id = $request_result['transaction_id'];
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
                                $resFlag = $this->cronJob->editTranslation($dataCron, $id);
                                $message = "Dear " . $actid . " Your account is renewed in the broadband plan of " . $cpnTypeId;
                                $query = array(
                                    //'mobile' => '9833268386',
                                    'mobile' => $mobile,
                                    'message' => $message
                                );
                                $this->global['success'] = "Transaction Done Successfully";
                                $data = sendSms($query);
                            } else {
                                $this->global['error'] = $network_res['faultString'];
                            }
                        }
                    }
                }else{
                    $this->global['success'] = "Plan Already Activated";
                }
            }
        } else {
            $this->global['error'] = "Please Select Valid Transaction!";
        }

        $this->loadViews("upcoming", $this->global, NULL, NULL);
    }


}
