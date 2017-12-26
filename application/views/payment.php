<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");

$plan_query = mysqli_query($con, "SELECT * FROM tbl_plans WHERE Sr_no = $_POST[plan_id]");
$current_plan_row = mysqli_fetch_assoc($plan_query);
$amount_db = floor($current_plan_row['price_inc_of_tax']);

if ($amount_db != $_POST['amount']) {
    die("Prize Not Match");
}

$plan_id = $_POST['plan_id'];
$plan_api_id = $_POST['plan_api_id'];
$billing_cust_notes = $_POST['billing_cust_notes'];
$amount = $_POST['amount'];
$billing_cust_tel = $_POST['billing_cust_tel'];
$act_id = $_POST['act_id'];
$domain_id = $_POST['domain_id'];
$subs_no = $_POST['subs_no'];
$old_pan = $_POST['old_plan_id'];

$request_json = json_encode(array('subsno' => $subs_no,'cpnTypeId' => $plan_api_id, 'domid' => $domain_id,'old_pan' => $old_pan));

$insert_query = "INSERT INTO tbl_transaction(plan_id, plan_api_id, type, amount, mobile, actid, status,request_json) VALUES ($plan_id,'$plan_api_id','$billing_cust_notes',$amount,'$billing_cust_tel','$act_id','Processing','$request_json')";

if (mysqli_query($con, $insert_query)) {
    $Order_Id = mysqli_insert_id($con);
} else {
    die("Transaction Error");
}

// Merchant id provided by CCAvenue
$access_code = "AVXK74EK17AQ70KXQA";
$Merchant_Id = 154189;
// Item amount for which transaction perform
$Amount = $_POST['amount'];
// Unique OrderId that should be passed to payment gateway
// Unique Key provided by CCAvenue
$WorkingKey = "6BF6B9240E4CB596BA642AB58564EBE4";
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') == true ? 'https://' : 'http://';

$base_url = "https://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/';
// Success page URL
$Redirect_Url = $base_url."success.php";
require_once 'classes/ccave/Payment.php';
use Kishanio\CCAvenue\Payment as CCAvenueClient;

$ccavenue = new CCAvenueClient($Merchant_Id, $WorkingKey, $Redirect_Url);

// set details
$ccavenue->setAmount($Amount);
$ccavenue->setCurrency("INR");
$ccavenue->setOrderId($Order_Id);
$ccavenue->setBillingName($_POST['billing_cust_name']);
$ccavenue->setBillingAddress($_POST['billing_cust_address']);
$ccavenue->setBillingCity($_POST['billing_cust_city']);
$ccavenue->setBillingZip($_POST['billing_cust_zip']);
$ccavenue->setBillingState($_POST['billing_cust_state']);
$ccavenue->setBillingCountry($_POST['billing_cust_country']);
$ccavenue->setBillingEmail($_POST['billing_cust_email']);
$ccavenue->setBillingTel($_POST['billing_cust_tel']);
$ccavenue->setBillingNotes($_POST['billing_cust_notes']);

// copy all the billing details to chipping details
$ccavenue->billingSameAsShipping();

// get encrpyted data to be passed
$data = $ccavenue->getEncryptedData();

// merchant id to be passed along the param
$merchant = $ccavenue->getMerchantId();


?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
<!-- Request -->
<form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
    <?php
    echo '<input type=hidden name=encRequest value="' . $data . '"">';
    echo '<input type=hidden name=Merchant_Id value="' . $merchant . '">';
    echo '<input type=hidden name=access_code value="' . $access_code . '">';
    ?>
</form>

<script language='javascript'>document.redirect.submit();</script>
</body>
</html>