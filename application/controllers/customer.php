<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/xmlrpc.inc';
require APPPATH . '/libraries/xmlrpcs.inc';
require APPPATH . '/libraries/ccave/Payment.php';
require APPPATH . '/libraries/NetworkAPI.php';
require APPPATH . '/libraries/dompdf/autoload.inc.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';
require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;
use Dompdf\Options;
use Kishanio\CCAvenue\Payment as CCAvenueClient;

class Customer extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('transaction');
        $this->load->model('cronJob');
        $this->load->model('customersubmodel');
        $this->load->model('tbl_plans');
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("customer", $this->global, NULL, NULL);
    }

    public function search()
    {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->global['pageTitle'] = 'CodeInsect : Customer';
        $flagA = false;
        $flagB = false;
        if (isset($_POST["contactnumber"]) || isset($_POST["user_id"])) {
            $datas['data'] = '';
            $paramurl = '';
            $contactnumber = $_POST["contactnumber"];
            $user_id = $_POST["user_id"];
            if ($contactnumber == '' && $user_id == '') {
                $this->global['error'] = "You must enter Value like User Id or Contact Number!";
            } else {
                if ($contactnumber == '') {
                    $datas['data'] = $user_id;
                    $paramurl = "unify.getAccountDetails";
                } else {
                    $datas['data'] = $contactnumber;
                    $paramurl = "unify.getAccountDetailsByMobileNumber";
                }
            }

            $cli1 = new xmlrpc_client('http://103.66.48.2/unifyv3/xmlRPC.do');
            $cli1->setDebug(0);
            $cli1->setCredentials('sherie', 'PS@ShERiE');

            $params = array(
                new xmlrpcval($datas['data'], 'string'),
            );
            $msg1 = new xmlrpcmsg($paramurl, $params);
            $resp1 = $cli1->send($msg1);
            $msg1->serialize();
            $resp1->serialize();
            $result = "";

            if (!$resp1) {
                $result = 'Communication error: ' . $cli1->errstr;
            }

            if (!($resp1->faultCode())) {
                $val1 = $resp1->payload;
                $result = xmlrpc_decode($val1, 'iso-8859-1');
                if ($contactnumber == '') {
                    $this->global['result'] = $result;
                } else {
                    $this->global['result'] = $result[0];
                    $this->global['Mobile'] = $contactnumber;
                }
                $customer_data = $this->global['result'];
                $flagA = 'success';
            } else {
                $flagA = 'failure';
                $this->global['error'] = "No Account Found With This Mobile No.";
            }
            $data = array(
                'type' => 'getAccountDetailsByMobileNumber',
                'mobile' => $contactnumber,
                'actid' => $user_id,
                'transaction_date' => date('Y-m-d'),
                'status' => $flagA,
                'request_json' => json_encode($datas),
                'response' => json_encode($result),
            );

            $this->transaction->addNewTranslation($data);
            if ($flagA == true) {
                $cli1 = new xmlrpc_client('http://103.66.48.2/unifyv3/xmlRPC.do');
                $cli1->setDebug(0);
                $cli1->setCredentials('sherie', 'PS@ShERiE');
                $params = array(
                    new xmlrpcval($customer_data['actid'], 'string'),
                );
                $msg1 = new xmlrpcmsg("unify.getSubscriptions", $params);
                $resp1 = $cli1->send($msg1);
                $msg1->serialize();
                $resp1->serialize();
                $result = "";
                if (!$resp1) {
                    $result = 'Communication error: ' . $cli1->errstr;
                }
                if (!($resp1->faultCode())) {
                    $val1 = $resp1->payload;
                    $result = xmlrpc_decode($val1, 'iso-8859-1');
                    $this->global['subscriptions'] = reset($result);
                    $subscriptions = $result;
                    $flagB = 'success';
                } else {
                    $flagB = 'failure';
                    $this->global['error'] = "No subscriptions Found With This Mobile No.";
                }
                $data = array(
                    'type' => 'getSubscriptions',
                    'mobile' => $contactnumber,
                    'actid' => $user_id,
                    'transaction_date' => date('Y-m-d'),
                    'status' => $flagB,
                    'request_json' => json_encode($params),
                    'response' => json_encode($result),
                );
                $this->transaction->addNewTranslation($data);
                if ($flagA && $flagB) {
                    $subscriptions = end($subscriptions);
                    $plans = $this->tbl_plans->getPlan();
                    // get current Plan
                    $current_plan = $subscriptions['svcdescr'];
                    $current_expiry = strtotime(date_format(date_create($subscriptions['expirydt']), "Y-m-d"));
                    $current_plan_row = $this->tbl_plans->getPlanInfo($current_plan);
                    $plan_val_months = explode(' ', $current_plan_row[0]->validity);
                    $new_exp = date("Y-m-d", strtotime("+" . $plan_val_months[0] . " month", $current_expiry));
                    $amount = floor($current_plan_row[0]->price_inc_of_tax);
                    $round_off = $current_plan_row[0]->price_inc_of_tax - $amount;
                    $this->global['amount'] = $amount;
                    $this->global['new_exp'] = $new_exp;
                    $this->global['plans'] = $plans;
                    $this->global['current_plan_row'] = $current_plan_row;
                    $this->global['current_plan'] = $current_plan;
                }
            }
        } else {
        }
        $this->loadViews("customer", $this->global, NULL, NULL);
    }

    public function getPayDetails()
    {
        $network = new NetworkAPI();
        $mobile = $_POST['mobile'];
        $id = $_POST['id'];
        $customer_data = $network->getAccountDetailsByMobileNumber($mobile);

        $customer_data = $customer_data[0];
        $subscriptions = $network->getSubscriptions($customer_data['actid']);
        $subscriptions = $subscriptions[0];
        // get current Plan
        $current_plan = $subscriptions['svcdescr'];
        $current_expiry = strtotime(date_format(date_create($subscriptions['expirydt']), "Y-m-d"));

        $current_plan_row = $this->tbl_plans->getPlanData(array('column' => 'Sr_no', 'value' => $id));

        $plan_val_months = explode(' ', $current_plan_row[0]->validity)[0];

        $new_exp = date("Y-m-d", strtotime("+" . $plan_val_months . " month", $current_expiry));
        $amount = floor($current_plan_row[0]->price_inc_of_tax);
        $round_off = $current_plan_row[0]->price_inc_of_tax - $amount;

        $data = array(
            'id' => $current_plan_row[0]->Sr_no,
            'plan_id' => $current_plan_row[0]->coupon_type_id,
            'expiry_date' => $new_exp,
            'plan_name' => $current_plan_row[0]->rate_plan,
            'plan_amount' => $current_plan_row[0]->price_inc_of_tax,
            'tax' => $current_plan_row[0]->price_inc_of_tax,
            'round_off' => $round_off,
            'amount' => $amount,
        );
        echo json_encode($data);
    }

    public function onlinepay()
    {
        $this->global['pageTitle'] = 'CodeInsect : Online Pay';

        $this->loadViews("onlinepay", $this->global, NULL, NULL);
    }

    public function editCustomer()
    {
        $this->global['pageTitle'] = 'CodeInsect : Edit Customer';
        $this->loadViews("editcustomer", $this->global, NULL, NULL);
    }

    public function paymentData()
    {
        if ($_POST['activeplan'] == 'immediate') {
            $flag = true;
        } elseif ($_POST['activeplan'] == 'afterexpire') {
            $flag = true;
        } elseif ($_POST['activeplan'] == 'schedulerecharge') {
            $flag = true;
        } else {
            $flag = false;
        }
        $current_plan_row = $this->tbl_plans->getPlanData(array('column' => 'Sr_no', 'value' => $_POST['plan_id']));
        $amount_db = floor($current_plan_row[0]->price_inc_of_tax);
        if ($amount_db != $_POST['amount']) {
            $this->global['error'] = "Prize Not Match";
        }
        else {
            $plan_id = $_POST['plan_id'];
            $plan_api_id = $_POST['plan_api_id'];
            $billing_cust_notes = $_POST['billing_cust_notes'];
            $amount = $_POST['amount'];
            $billing_cust_tel = $_POST['billing_cust_tel'];
            $act_id = $_POST['act_id'];
            $domain_id = $_POST['domain_id'];
            $subs_no = $_POST['subs_no'];
            $old_pan = $_POST['old_plan_id'];
            $expiry_date = $_POST['expiry_date'];

            $request_json = json_encode(array('subsno' => $subs_no, 'cpnTypeId' => $plan_api_id, 'activeplan' => $_POST['activeplan'], 'domid' => $domain_id, 'old_pan' => $old_pan));

            $data = array(
                'plan_id' => $plan_id,
                'plan_api_id' => $plan_api_id,
                'type' => $billing_cust_notes,
                'amount' => $amount,
                'mobile' => $billing_cust_tel,
                'activatetype' => $_POST['activeplan'],
                'actid' => $act_id,
                'transaction_date' => date('Y-m-d'),
                'expiry_date' => $expiry_date,
                'status' => 'Processing',
                'payment_mode' => $_POST['paymentmode'],
                'chequnodate' => $_POST['chqunumberdate'],
                'request_json' => $request_json,
                'created_by' => $this->vendorId,
            );

            $Order_Id = $this->transaction->addNewTranslation($data);
            // Merchant id provided by CCAvenue

            $order_id = $Order_Id;
            $data = array(
                'column' => 'id',
                'value' => $order_id,
            );
            $request_result = $this->transaction->getTranslationData($data);
            $request_arr = json_decode($request_result[0]->request_json, true);
            $request_json = $request_arr;
            $actid = $request_result[0]->actid;
            $amount = $request_result[0]->amount;
            $mobile = $request_result[0]->mobile;
            $to = $request_result[0]->mobile;
            $subsno = $request_arr['subsno'];
            $cpnTypeId = $request_arr['cpnTypeId'];
            $domid = $request_arr['domid'];
            $old_plan = $request_arr['old_pan'];
            $activeplan = $request_arr['activeplan'];
            $date = date("Ymd\TH:i:s", time());

            $network = new NetworkAPI();
            $user_details = $network->getAccountDetailsByMobileNumber($mobile)[0];
            $to = $user_details['email'];
            $message = "Dear customer, we have received  payment of Rs." . $amount . " for your Srilakshmi Networks customer ID " . $actid . " .Your account balance is Rs 0.00.";
            $query = array(
                //'mobile' => '9833268386',
                'mobile' => $mobile,
                'message' => $message
            );
            $network_res = '';
            $data = sendSms($query);
            $message = '<html><body>';
            $message .= '<h1>Hi ' . $user_details['fname'] . ' ' . $user_details['lname'] . '!</h1><br>';
            $message .= '<p>Dear customer, we have received  payment of Rs.' . $amount . ' for your Sri Lakshmi Networks customer ID ' . $user_details['actid'] . ' .Your account balance is Rs ' . $user_details['balance'] . '.</p>';
            $message .= '</body></html>';
            $filename = dompdf($message);
            send_mail($message, $to, $filename);

            /*if ($old_plan != $cpnTypeId) {*/
            $network_res = $network->changeSubscription($subsno, $cpnTypeId, $date);
            //$cp_res = $network->changeSubscription($subsno, $cpnTypeId, $date);
            if (!is_array($network_res)) {
                $subscriptions = $network->getSubscriptions($actid);
                $subscriptions = $subscriptions[0];
                $subsno = $subscriptions['subsno'];
                $cpnTypeId = $subscriptions['pkgid'];
                $domid = $subscriptions['domid'];

            } else {
                $this->global['success'] = "Change Plan Failed";
            }
            /* }*/
            $request_json = json_encode(array('subsno' => $subsno, 'cpnTypeId' => $cpnTypeId, 'activeplan' => $activeplan, 'domid' => $domid, 'old_pan' => $old_plan));

            if ($activeplan == 'immediate') {
                $network_res = $network->rechargeSubscriptionViaCouponType($subsno, $cpnTypeId, $domid);
                if (!is_array($network_res)) {
                    $this->global['order_id'] = $Order_Id;
                    $this->global['success'] = "Renewal transaction Success";
                } else {
                    $this->global['error'] = $network_res['faultString'];
                }
                $message = "Dear " . $actid . " Your account is renewed in the broadband plan of " . $cpnTypeId;
                $query = array(
                    //'mobile' => '9833268386',
                    'mobile' => $mobile,
                    'message' => $message
                );
                $data = sendSms($query);

            } else {
                $data = array(
                    'transaction_id' => $order_id,
                    'expire' => $request_result[0]->expiry_date,
                    'status' => 'new'
                );
                $this->cronJob->addNewCron($data);
                $this->global['order_id'] = $Order_Id;
                $this->global['success'] = "Renewal transaction Success";
            }
            $data = array(
                'status' => "success",
                'request_json' => $request_json,
                'tran_response' => json_encode($network_res),
            );
            $resFlag = $this->transaction->editTranslation($data, $order_id);

        }
        $this->loadViews("customer", $this->global, NULL, NULL);
    }

    public function successfailure()
    {
        if (isset($_POST["encResp"])) {
            // Get Response
            $response = $_POST["encResp"];
            $network_res = '';
            $ccavenue = new CCAvenueClient('MAN_154189', '6BF6B9240E4CB596BA642AB58564EBE4', '');

            // Check if the transaction was successfull.
            $response_str = $ccavenue->response($response);
            $response_arr = explode('&', $response_str['data']);

            $response_json = json_encode($response_arr);
            $order_status = $response_str['status'];
            $order_id = explode('=', $response_arr[0])[1];
            $data = array(
                'column' => 'id',
                'value' => $order_id,
            );
            $request_result = $this->transaction->getTranslationData($data);
            $request_arr = json_decode($request_result[0]->request_json, true);
            $request_json = $request_arr;
            $actid = $request_result[0]->actid;
            $amount = $request_result[0]->amount;
            $mobile = $request_result[0]->mobile;
            $to = $request_result[0]->mobile;
            $subsno = $request_arr['subsno'];
            $cpnTypeId = $request_arr['cpnTypeId'];
            $domid = $request_arr['domid'];
            $old_plan = $request_arr['old_pan'];
            $activeplan = $request_arr['activeplan'];
            $date = date("Ymd\TH:i:s", time());

            $network = new NetworkAPI();
            $user_details = $network->getAccountDetailsByMobileNumber($mobile)[0];
            $to = $user_details['email'];
            if ($order_status == "success") {
                $message = "Dear customer, we have received  payment of Rs." . $amount . " for your Srilakshmi Networks customer ID " . $actid . " .Your account balance is Rs 0.00.";
                $query = array(
                    //'mobile' => '9833268386',
                    'mobile' => $mobile,
                    'message' => $message
                );

                $data = sendSms($query);
                $message = '<html><body>';
                $message .= '<h1>Hi ' . $user_details['fname'] . ' ' . $user_details['lname'] . '!</h1><br>';
                $message .= '<p>Dear customer, we have received  payment of Rs.' . $amount . ' for your Sri Lakshmi Networks customer ID ' . $user_details['actid'] . ' .Your account balance is Rs ' . $user_details['balance'] . '.</p>';
                $message .= '</body></html>';
                $filename = dompdf($message);
                send_mail($message, $to, $filename);

                if ($old_plan != $cpnTypeId) {
                    $network_res = $network->changeSubscription($subsno, $cpnTypeId, $date);
                    //$cp_res = $network->changeSubscription($subsno, $cpnTypeId, $date);
                    if (!is_array($network_res)) {
                        $subscriptions = $network->getSubscriptions($actid);
                        $subscriptions = $subscriptions[0];
                        $subsno = $subscriptions['subsno'];
                        $cpnTypeId = $subscriptions['pkgid'];
                        $domid = $subscriptions['domid'];

                    } else {
                        $this->global['success'] = "Change Plan Failed";
                    }
                }
                $request_json = json_encode(array('subsno' => $subsno, 'cpnTypeId' => $cpnTypeId, 'activeplan' => $activeplan, 'domid' => $domid, 'old_pan' => $old_plan));

                if ($activeplan == 'immediate') {
                    $network_res = $network->rechargeSubscriptionViaCouponType($subsno, $cpnTypeId, $domid);
                    if (!is_array($network_res)) {
                        $this->global['success'] = "Renewal transaction Success";
                    } else {
                        $this->global['error'] = $network_res['faultString'];
                    }
                    $message = "Dear " . $actid . " Your account is renewed in the broadband plan of " . $cpnTypeId;
                    $query = array(
                        //'mobile' => '9833268386',
                        'mobile' => $mobile,
                        'message' => $message
                    );
                    $data = sendSms($query);
                } else {

                    $data = array(
                        'transaction_id' => $order_id,
                        'expire' => $request_result[0]->expiry_date,
                        'status' => 'new'
                    );
                    $this->cronJob->addNewCron($data);
                    $this->global['success'] = "Renewal transaction Success";
                }
            } else {
                //ccccv $to = 'frederic.soreng@gmail.com';
                $message = '<html><body>';
                $message .= '<h1>Hi ' . $user_details['fname'] . ' ' . $user_details['lname'] . '!</h1><br>';
                $message .= '<p>Dear customer, Your payment of Rs.' . $amount . ' Failed for your Sri Lakshmi Networks customer ID ' . $user_details['actid'] . ' .Your account balance is Rs ' . $user_details['balance'] . '.</p>';
                $message .= '</body></html>';
                $filename = dompdf($message);
                send_mail($message, $to, $filename);
                $this->global['error'] = "Transaction Failed";
            }
            $data = array(
                'status' => $order_status,
                'response' => $response_json,
                'enc_response' => $response,
                'request_json' => $request_json,
                'tran_response' => json_encode($network_res),
            );
            $resFlag = $this->transaction->editTranslation($data, $order_id);

        } else {
            $this->global['error'] = "Transaction Failed";
        }
        $this->loadViews("customer", $this->global, NULL, NULL);
    }

    public function customerRequest()
    {
        $request_result = $this->customersubmodel->getsubscrinfo();
        $this->global['subrequest'] = $request_result;
        $this->loadViews("subcriptionrequest", $this->global, NULL, NULL);

    }

    public function dompdf()
    {
        $dd = "helo";
        echo $filename = dompdf($dd);
        send_mail($dd, '', $filename);
        //echo $path;
    }
}

?>