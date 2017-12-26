<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once('db_config.php');
require_once 'NetworkAPI.php';
require_once 'classes/ccave/Payment.php';
require_once 'classes/dompdf/autoload.inc.php';
require_once 'classes/PHPMailer/src/SMTP.php';
require_once 'classes/PHPMailer/src/Exception.php';
require_once 'classes/PHPMailer/src/PHPMailer.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;

$network = new NetworkAPI();

if (isset($_GET['invoice_id']))
    $order_id = base64_decode($_GET['invoice_id']);
else
    $order_id = $_POST['id_trans'];

if ($order_id == "") {
    die("No Bill Found!");
}

$request = mysqli_query($con, "SELECT * FROM tbl_transaction WHERE id = $order_id");
$request_result = mysqli_fetch_assoc($request);

if ($request_result['status'] != "success") {
    die("No Bill Found!");
}

$planId = $request_result['plan_id'];
$request_plan = mysqli_query($con, "SELECT * FROM tbl_plans WHERE Sr_no = $planId");
$request_result_plan = mysqli_fetch_assoc($request_plan);
$request_arr = json_decode($request_result['request_json'], true);
$response_arr = $request_result['response'];

$no_brackets = trim($request_result['response'], '[]'); //if you stored your array as comma delimited value, you would not need this.
$arr = explode(',', $no_brackets);

$Amount = explode("=", trim($arr['10'], '"'))[1];

$mobile = $request_result['actid'];
$customer_data = $network->getAccountDetails($mobile);
//$customer_data = $customer_data[0];
$to = $customer_data['email'];
$subscriptions = $network->getSubscriptions($mobile);
$mobile = $request_result['mobile'];
$invoicelink = $request_result['invoicelink'];
$subscriptions = $subscriptions[0];
function dompdf($dd)
{
    $unique_token = md5(date('Y-m-d H:i:s') . (rand() * 100000));
    $options = new Options();
    $options->setIsRemoteEnabled(true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($dd);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    $nameF = $unique_token . ".pdf";
    file_put_contents("pdfinvoice/" . $nameF, $output);
    $filename = "pdfinvoice/" . $nameF;
    return $filename;
}

function send_mail($message, $to, $filename)
{
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function

    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'tls://smtp.gmail.com:587'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'no-reply@srilakshminetworks.com'; // SMTP username
        $mail->Password = '@2P4$yEp3#i3'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->setFrom('no-reply@srilakshminetworks.com', 'noreply');
        $mail->addAddress('jaydipbhingradiya@gmail.com', 'frederic.soreng@gmail.com', 'Erick'); // Add a recipient
        $mail->addAddress('frederic.soreng@gmail.com', 'Erick'); // Add a recipient
        $mail->addAddress($to, 'Erick'); // Add a recipient
        //Attachments
        $mail->addAttachment($filename);
        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Transaction Status';
        $mail->Body = $message;

        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

?>
<?php
if (isset($_POST['id_trans'])) {
    $order_id = $_POST['id_trans'];
    if ($order_id == "") {
        die("No Bill Found!");
    }
    $request = mysqli_query($con, "SELECT * FROM tbl_transaction WHERE id = $order_id");
    $request_result = mysqli_fetch_assoc($request);
    if ($request_result['status'] != "success") {
        die("No Bill Found!");
    }

    $planId = $request_result['plan_id'];
    $request_plan = mysqli_query($con, "SELECT * FROM tbl_plans WHERE Sr_no = $planId");
    $request_result_plan = mysqli_fetch_assoc($request_plan);
    $request_arr = json_decode($request_result['request_json'], true);
    $response_arr = $request_result['response'];

    $no_brackets = trim($request_result['response'], '[]'); //if you stored your array as comma delimited value, you would not need this.
    $arr = explode(',', $no_brackets);

    $Amount = explode("=", trim($arr['10'], '"'))[1];

    $mobile = $request_result['actid'];
    $customer_data = $network->getAccountDetails($mobile);
//$customer_data = $customer_data[0];
    $to = $customer_data['email'];
    $subscriptions = $network->getSubscriptions($mobile);
    $mobile = $request_result['mobile'];
    $subscriptions = $subscriptions[0];

    $dateend = date_format(date_create($subscriptions['expirydt']), "Y-m-d");
    $message = "Dear Customer, <br><br>
    
    Thank you for choosing Sri Lakshmi Networks as your internet service provider.<br><br>
    
    Your payment of Rs." . $request_result_plan['price_inc_of_tax'] . " towards Broadband Subscription is Successful,<br><br>
    
    Transaction ID : 17-18/" . $order_id . "<br>
    Date : " . $request_result['created_at'] . "<br>
    Total Payable :  " . $request_result_plan['price_inc_of_tax'] . "<br>
    Amount paid :  " . $request_result_plan['price_inc_of_tax'] . "<br><br>
    
    Name : " . $customer_data['actid'] . "<br>
    Contact : " . $mobile . "<br>
    Email : help@srilakshminetworks.com<br><br>
    We have attached your invoice below.<br><br>
    In case of any issue with broadband services, please contact:<br><br>
    
    Sri Lakshmi Networks<br>
    60-2,Ananthanagar layout, first phase, first left cross, krishnappa building, behind canara bank atm, Bengaluru 560100<br>
    Ph No : 08049587000
    <br><br>
    
    Regards<br>
    Sri Lakshmi Networks Team";
    if ($_POST['email'] == '') {
        echo 'You Must Enter Email Address!';
    }
    $filename = $invoicelink;
    send_mail($message, $_POST['email'], $filename);
    echo 'Invoice Send Successfully';

}

?>