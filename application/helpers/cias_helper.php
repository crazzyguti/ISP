<?php use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;

if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * This function is used to print the content of any data
 */
function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * This function used to get the CI instance
 */
if (!function_exists('get_instance')) {
    function get_instance()
    {
        $CI = &get_instance();
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if (!function_exists('getHashedPassword')) {
    function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 * @param {string} $hashedPassword : This is hashed password
 */
if (!function_exists('verifyHashedPassword')) {
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }
}

/**
 * This method used to get current browser agent
 */
if (!function_exists('getBrowserAgent')) {
    function getBrowserAgent()
    {
        $CI = get_instance();
        $CI->load->library('user_agent');

        $agent = '';

        if ($CI->agent->is_browser()) {
            $agent = $CI->agent->browser() . ' ' . $CI->agent->version();
        } else if ($CI->agent->is_robot()) {
            $agent = $CI->agent->robot();
        } else if ($CI->agent->is_mobile()) {
            $agent = $CI->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        return $agent;
    }
}

if (!function_exists('setProtocol')) {
    function setProtocol()
    {
        $CI = &get_instance();

        $CI->load->library('email');

        $config['protocol'] = PROTOCOL;
        $config['mailpath'] = MAIL_PATH;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['smtp_user'] = SMTP_USER;
        $config['smtp_pass'] = SMTP_PASS;
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";

        $CI->email->initialize($config);

        return $CI;
    }
}

if (!function_exists('emailConfig')) {
    function emailConfig()
    {
        $CI->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['mailpath'] = MAIL_PATH;
        $config['charset'] = 'UTF-8';
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
    }
}

if (!function_exists('resetPasswordEmail')) {
    function resetPasswordEmail($detail)
    {
        $data["data"] = $detail;
        // pre($detail);
        // die;

        $CI = setProtocol();

        $CI->email->from(EMAIL_FROM, FROM_NAME);
        $CI->email->subject("Reset Password");
        $CI->email->message($CI->load->view('email/resetPassword', $data, TRUE));
        $CI->email->to($detail["email"]);
        $status = $CI->email->send();

        return $status;
    }
}

if (!function_exists('setFlashData')) {
    function setFlashData($status, $flashMsg)
    {
        $CI = get_instance();
        $CI->session->set_flashdata($status, $flashMsg);
    }
}

function saveNewAccountWithSubscription($datas)
{

    // $cli1 = new xmlrpc_client(unify_IP . '/unifyv3/xmlRPC.do', UNIFY_IP);
    $cli1 = new xmlrpc_client('http://103.66.48.2/unifyv3/xmlRPC.do');
    $cli1->setDebug(0);
    $cli1->setCredentials(UNIFY_USR, UNIFY_PWD);

    $params = array(
        // new xmlrpcval($datas['mobileno'], 'string'),
        // new xmlrpcval($datas['domid'], 'string'),
        new xmlrpcval($datas['actid'], 'string'),
        new xmlrpcval($datas['devIDFilter'], 'string'),

    );

    $msg1 = new xmlrpcmsg("unify.getAccountDetailsByNetIDUPWD", $params);
    $resp1 = $cli1->send($msg1);

    echo "<pre><br>";
    $FTTH_LOG = "<pre><br>";
    $FTTH_LOG .= "unify.saveNewAccountWithSubscription request param:<br>" . $msg1->serialize() . "<br>";
    $FTTH_LOG .= "\n######################################\n<br>";
    $FTTH_LOG .= "unify.saveNewAccountWithSubscription response:<br>" . $resp1->serialize() . "<br>";

    $result = "";
    if (!$resp1) {
        $result = 'Communication error: ' . $cli1->errstr;
        return $result;
    }

    if (!($resp1->faultCode())) {
        $val1 = $resp1->payload;
        $result = xmlrpc_decode($val1, 'iso-8859-1');
    } else {
        echo 'Fault Code: ' . $resp1->faultCode() . "\n";
        echo 'Fault Reason: ' . $resp1->faultString() . "\n";
    }

    return $result;
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
function send_mail($message,$to,$filename)
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
        $mail->addAddress('jaydipbhingradiya@gmail.com', 'Erick'); // Add a recipient
        $mail->addAddress($to, ''); // Add a recipient
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

?>