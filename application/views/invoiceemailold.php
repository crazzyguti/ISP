<?php

include_once "xmlrpc.inc";
require_once('db_config.php');
define("UNIFY_IP", '103.66.48.2');
define("UNIFY_USR", 'sherie');
define("UNIFY_PWD", 'PS@ShERiE');
define('domId', "slbbtest");
define('Connection', $con);

/**
 *
 */
class NetworkAPI
{
    public $cli1;
    public $con;

    function __construct()
    {
        $this->cli1 = new xmlrpc_client('http://103.66.48.2/unifyv3/xmlRPC.do');
        $this->cli1->setDebug(0);
        $this->cli1->setCredentials(UNIFY_USR, UNIFY_PWD);
        $this->con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");
    }

    function getAccountDetailsByMobileNumber($mobile)
    {
        $params = array(
            new xmlrpcval($mobile, 'string'),
        );

        $msg1 = new xmlrpcmsg("unify.getAccountDetailsByMobileNumber", $params);
        $resp1 = $this->cli1->send($msg1);
        $resp1->serialize();

        $val1 = $resp1->payload;
        $result = xmlrpc_decode($val1, 'UTF-8');

        return $result;
    }

    function getAccountDetails($act_id)
    {
        $params = array(
            new xmlrpcval($act_id, 'string'),
        );

        $msg1 = new xmlrpcmsg("unify.getAccountDetails", $params);
        $resp1 = $this->cli1->send($msg1);
        $resp1->serialize();

        $val1 = $resp1->payload;
        $result = xmlrpc_decode($val1, 'UTF-8');

        return $result;
    }

    function getSubscriptions($actid)
    {
        $params = array(
            new xmlrpcval($actid, 'string'),
        );

        $msg1 = new xmlrpcmsg("unify.getSubscriptions", $params);
        $resp1 = $this->cli1->send($msg1);
        $resp1->serialize();

        $val1 = $resp1->payload;
        $result = xmlrpc_decode($val1, 'UTF-8');

        return $result;
    }

    function getPayDetails($mobile, $id)
    {
        $customer_data = $this->getAccountDetailsByMobileNumber($mobile);

        $customer_data = $customer_data[0];
        $subscriptions = $this->getSubscriptions($customer_data['actid']);
        $subscriptions = $subscriptions[0];
        // get current Plan
        $current_plan = $subscriptions['svcdescr'];
        $current_expiry = strtotime(date_format(date_create($subscriptions['expirydt']), "Y-m-d"));

        $plan_query = mysqli_query($this->con, "SELECT * FROM tbl_plans WHERE Sr_no = '$id'");
        $current_plan_row = mysqli_fetch_assoc($plan_query);
        $plan_name_mod = str_replace('_', ' ', substr($current_plan_row['coupon_type_id'], 1));
        $plan_val_months = explode(' ', $current_plan_row['validity'])[0];

        $new_exp = date("Y-m-d", strtotime("+" . $plan_val_months . " month", $current_expiry));
        $amount = floor($current_plan_row['price_inc_of_tax']);
        $round_off = $current_plan_row['price_inc_of_tax'] - $amount;
        $tx=$current_plan_row['CGST'] + $current_plan_row['SGST'];
        $data = array(
            'id' => $current_plan_row['Sr_no'],
            'plan_id' => $current_plan_row['coupon_type_id'],
            'plan_name_mod' => $plan_name_mod,
            'expiry_date' => $new_exp,
            'plan_name' => $current_plan_row['rate_plan'],
            'plan_amount' => $current_plan_row['price_inc_of_tax'],
            'base' => $current_plan_row['base'],
            'CGST' => $current_plan_row['CGST'],
            'SGST' => $current_plan_row['SGST'],
            'tax' => $tx,
            'round_off' => $round_off,
            'amount' => $amount,
        );
        return $data;
    }

    public function rechargeSubscriptionViaCouponType($subsno, $cpnTypeId, $domname)
    {
        $params = array(

            new xmlrpcval($subsno, 'int'),
            new xmlrpcval($domname, 'string'),
            new xmlrpcval($cpnTypeId, 'string'),
            new xmlrpcval(array(
                // new xmlrpcval("1.00", 'double'),
                // new xmlrpcval("2017/11/17", 'date'),
                // new xmlrpcval("transactionDescription", 'string'),
                // new xmlrpcval("25", 'int'),
                // new xmlrpcval("instrumentDescription", 'string'),
                // new xmlrpcval("SBI", 'string'),
                // new xmlrpcval("Baroda", 'string'),
                // new xmlrpcval("12345", 'string'),
                // new xmlrpcval("2017/11/17", 'date'),
            ), "struct"),
        );

        $msg1 = new xmlrpcmsg("unify.rechargeSubscriptionViaCouponType", $params);
        $resp1 = $this->cli1->send($msg1);
        $resp1->serialize();

        $val1 = $resp1->payload;
        $result = xmlrpc_decode($val1, 'UTF-8');

        return $result;
    }

    function changeSubscription($subsno, $pkgid, $date)
    {
        $params = array(
            new xmlrpcval($subsno, 'int'),
            new xmlrpcval($pkgid, 'string'),
            new xmlrpcval($date, 'dateTime.iso8601'),
            new xmlrpcval(1, 'int'),
            new xmlrpcval(1, 'int'),
            new xmlrpcval(0, 'int'),
            new xmlrpcval(0, 'int'),
        );

        $msg1 = new xmlrpcmsg("unify.changeSubscription", $params);
        $resp1 = $this->cli1->send($msg1);
        $resp1->serialize();

        $val1 = $resp1->payload;
        $result = xmlrpc_decode($val1, 'UTF-8');

        return $result;
    }

}

if (!empty($_POST)) {
    if (@$_POST['method'] == 'getPayDetails') {
        $network = new NetworkAPI();
        $mobile = @$_POST['mobile'];
        $id = $_POST['id'];
        echo json_encode($network->getPayDetails($mobile, $id));
    }
}

// print_r($network->getAccountDetailsByMobileNumber('9884858059'));