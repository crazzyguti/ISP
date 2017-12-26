<?php
require_once('db_config.php');
require_once 'NetworkAPI.php';
require_once 'classes/ccave/Payment.php';
use Kishanio\CCAvenue\Payment as CCAvenueClient;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

//Load composer's autoloader
require 'classes/PHPMailer/src/SMTP.php';
require 'classes/PHPMailer/src/Exception.php';
require 'classes/PHPMailer/src/PHPMailer.php';

$network = new NetworkAPI();
// Get Response
$response = $_POST["encResp"];

$ccavenue = new CCAvenueClient('MAN_154189', '6BF6B9240E4CB596BA642AB58564EBE4','');

// Check if the transaction was successfull.
$response_str = $ccavenue->response($response);
$response_arr = explode('&',$response_str['data']);

$response_json = json_encode($response_arr);
$order_status = $response_str['status'];

$order_id = explode('=',$response_arr[0])[1];
$to_email = explode('=',$response_arr[18])[1];
$Amount = explode("=", trim($response_arr[10], '"'))[1];

$request = mysqli_query($con, "SELECT * FROM tbl_transaction WHERE id = $order_id");
$request_result = mysqli_fetch_assoc($request);
$request_arr = json_decode($request_result['request_json'],true);
$subsno = $request_arr['subsno'];
$cpnTypeId = $request_arr['cpnTypeId'];
$domid = $request_arr['domid'];
$old_plan = $request_arr['old_pan'];
$date = date("Ymd\TH:i:s", time());

$actid = $request_result['actid'];
$m_mobile = $request_result['mobile'];
$is_advance = $request_result['is_advance'];
$api_response = '';
$user_details = $network->getAccountDetailsByMobileNumber($m_mobile)[0];
$to = $user_details['email'];
if ($order_status == "success") {


    //Send Transaction Mail

    $to = $user_details['email'];
    $message = '<html><body>';
    $message .= '<h1>Hi '.$user_details['fname'].' '.$user_details['lname'].'!</h1><br>';
    $message .= '<p>Dear customer, we have received  payment of Rs.'.$Amount.' for your Sri Lakshmi Networks customer ID '.$actid.' .Your account balance is Rs '.$user_details['balance'].'.</p>';
    $message .= '</body></html>';
    send_mail($message,$to);

    $message = "Dear customer, we have received  payment of Rs." . $Amount . " for your Sri Lakshmi Networks customer ID " . $actid . " .Your account balance is Rs ".$user_details['balance'];
    $query = array(
        //'mobile' => '9833268386',
        'mobile' => $m_mobile,
        'message' => $message
    );

    $data = sendSms($query);

    if($is_advance == 1)
    {
        $cron_sql = "INSERT INTO cronJob(transaction_id,expire) VALUES ($order_id,'$request_result[transaction_date]')";
        if (mysqli_query($con, $cron_sql)) {
            //Mail

            $file = 'invoice.php?invoice_id=' . base64_encode($order_id);
            header('Location:invoice.php?invoice_id=' . base64_encode($order_id));
            echo "Transaction Success.";
        } else {
            echo "Transaction Failed.";
        }
    }
    else
    {
        //if($old_plan != $cpnTypeId)
        //{
        $cp_res = $network->changeSubscription($subsno, $cpnTypeId, $date);
        if(!is_array($network_res))
        {
            $subscriptions = $network->getSubscriptions($actid);
            $subscriptions = $subscriptions[0];
            $subsno =$subscriptions['subsno'];
            $cpnTypeId = $subscriptions['pkgid'];
            $domid = $subscriptions['domid'];
        }
        else
        {
            die("Change Plan Failed");
        }
        //}

        $network_res = $network->rechargeSubscriptionViaCouponType($subsno, $cpnTypeId, $domid);
        $api_response = is_array($network_res) ? json_encode($network_res) : $network_res;

        if(!is_array($network_res))
        {
            header('Location:invoice.php?invoice_id=' . base64_encode($order_id));
            $message = "Dear " . $actid . " Your account is renewed in the broadband plan of " . $cpnTypeId;
            $query = array(
                //'mobile' => '9833268386',
                'mobile' => $m_mobile,
                'message' => $message
            );
            $data = sendSms($query);
        }
        else
        {
            echo $network_res['faultString'];
        }
    }
    $sql = "UPDATE tbl_transaction SET status='$order_status', response='$response_json', enc_response = '$response', tran_response = '$api_response'  WHERE id=$order_id";

    if (mysqli_query($con, $sql)) {
    } else {
        var_dump(mysqli_error($con));
        die;
    }
}
else
{
    $message = '<html><body>';
    $message .= '<h1>Hi '.$user_details['fname'].' '.$user_details['lname'].'!</h1><br>';
    $message .= '<p>Dear customer, Your payment of Rs.'.$Amount.' Failed for your Sri Lakshmi Networks customer ID '.$user_details['actid'].' .Your account balance is Rs '.$user_details['balance'].'.</p>';
    $message .= '</body></html>';
    send_mail($message,$to);
    echo "transaction Failed";
}
function sendSms($data)
{
    $ch = curl_init();
    $query = http_build_query([
        'user' => '9980921629',
        'pass' => 'Crackleslbbs',
        'sender' => 'SLKBBS',
        'phone' => $data['mobile'],
        'text' => $data['message'],
        'priority' => 'ndnd',
        'stype' => 'normal',
    ]);
    $url = 'http://bulksms.7hsms.in/api/sendmsg.php?' . $query;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function send_mail($message,$to)
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
        $mail->addAddress('frederic.soreng@gmail.com', 'Erick'); // Add a recipient


        //Attachments

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