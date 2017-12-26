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

$order_id = base64_decode($_GET['invoice_id']);
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
 if($request_result['activatetype'] != 'immediate'){
     $request_result['transaction_date'] = '';
     $request_result['new_plan_exp']  = '';
     $date = date_format(date_create($subscriptions['expirydt']), "Y-m-d");

     $todatedata = $date;
     $fromdatedata = date('Y-m-d', strtotime($date. ' + 30 days'));
 }else{
     $todatedata = date_format(date_create($subscriptions['startdt']), "Y-m-d");
     $fromdatedata = date_format(date_create($subscriptions['expirydt']), "Y-m-d");
 }

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title> Sri Lakshmi Networks</title>
        <link rel="shortcut icon" href="favicon.png" type="image/ico">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="Pragma" content="no-cache">

        <style>
            body,
            html {
                height: 100%;
                font-size: 8px;
            }

            body {
                font-family: roboto;
                color: #595959;
                margin: 0px;
                padding: 0px;
            }

            .boxMain {
                width: 900px;
                margin: 0px auto;
                border: 1px solid #000;
                background: #fff;
            }

            .termLine {
                border: 1px solid #000;
                width: 890;
                border-radius: 3px;
            }

            .left {
                width: 500px;
                float: left;
            }

            .rightInvoice {
                width: 369px;
                float: left;
            }

            .leftContent {
                padding-left: 29px;
                margin: 0.6em;
            }

            .leftul {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .leftul li {
                display: inline-block;
                /* padding-bottom: 6px; */
                font-weight: 500;
                font-size: 15px;
            }

            .rightInvoice table tr td {
                font-size: 15px;
                padding-bottom: 10px;
                font-weight: 500;
            }

            .rightInvoice1 table tr td {
                font-size: 15px;
                padding-bottom: 10px;
                font-weight: 500;
            }

            .sentenceFull {
                float: none;
                clear: both;
                padding-left: 35px;
                margin-bottom: 5px;
                margin-top: 5px;
            }

            .sentenceFull1 {
                margin-bottom: 20px;
                margin-top: 28px;
            }

            .sentenceFull span {
                margin-bottom: 14px;
                color: #034ea2;
                font-size: 1.5em;
                font-weight: 500;
            }

            .sentenceFull1 span {
                margin-bottom: 4px;
                color: #034ea2;
                margin-top: 0px;
                /* margin-left: -3px; */
                font-size: 1.5em;
                font-weight: 500;
            }

            .tableFull tbody:before {
                content: "-";
                display: block;
                line-height: 1em;
                color: transparent;
            }

            .tableFull tbody:after {
                content: "-";
                display: block;
                line-height: 1em;
                color: transparent;
            }

            .tableFull th {
                font-size: 17px;
            }

            .tableFull td {
                text-align: center;
                /* background: #034ea2; */
                color: #034ea2;
                border-radius: 7px;
                font-size: 15px;
                font-weight: 600 !important;
            }

            .tableColorValue {
                padding-left: 8px;
                background: #034ea2;
                padding-top: 6px;
                padding-bottom: 5px;
                padding-right: 30px;
                border-radius: 12px;
                padding-left: 28px;
                color: #fff;
            }

            .leftDown {
                width: 420px;
                float: left;
            }

            .leftDownContent {
                background: #f6f6f6;
                overflow: auto;
                padding-left: 0px;
            }

            .rightDown {
                width: 420px;
                float: left;
            }

            .ulTable {
                padding-left: 14px;
                margin: 0;
            }

            .charges {
                width: 240px;
            }

            .charges1 {
                width: 60px;
            }

            .charges,
            .charges1 {
                list-style-type: none;
                margin: 0;
                padding: 0;
                display: inline-block;
                *display: inline;
                zoom: 1;
                float: left;
                padding-left: 17px;
            }

            .charges li {
                font-size: 15px;
                padding-bottom: 10px;
                font-weight: 500;
            }

            .charges1 li {
                font-size: 14px;
                padding-bottom: 10px;
                text-align: right;
            }

            .colorB {
                color: #034ea2 !important;
            }

            .termsContent span {
                color: #034ea2;
                font-size: 18px;
                font-weight: 500;
                line-height: 41px;
                margin: 10px 0;
            }

            .termsContent p {
                font-size: 12px;
                font-weight: 500;
            }

            .leftSpace {
                padding-left: 18px;
            }

            .oddOut {
                color: #595959;
            }

            .rightInvoice hr {
                margin: 0px !important;
                border-top: 1px solid #d9d9d9;
            }

            @media screen, print {
                .rightInvoice h2 {
                    color: #034ea2 !important;
                }

                .sentenceFull span {
                    color: #034ea2 !important;
                }

                .tableFull td {
                    color: #034ea2 !important;
                }

                .ulTable span {
                    color: #034ea2 !important;
                }

                .colorVAlueX {
                    color: #034ea2 !important;
                }

                .leftDownContent {
                    background: #f6f6f6 !important;
                }

                .oddOut {
                    color: #595959 !important;
                }

                .rightInvoice hr {
                    border-top: 1px solid #d9d9d9 !important;
                }

                .colorB {
                    color: #034ea2 !important;
                }

                .footer,
                #non-printable {
                    display: none !important;
                }

                #printable {
                    display: block;
                }
            }
        </style>

        <style type="text/css" media="print">
            @page {
                size: auto;
                /* auto is the initial value */
                margin: 0mm;
                /* this affects the margin in the printer settings */
            }
        </style>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    </head>
    <body>
    <div class="">
        <div class="boxMain">
            <div style="padding-top: 8px;">
                <div class="left">
                    <div class="leftContent">
                        <img src="./images/splogo.jpg" alt="logo" style="margin-top:9px;">
                        <p style="padding-top: 18px;"><b style="font-size: 19px;"><?= $customer_data['fname'] ?>,</b>
                            <br> <span
                                    style="letter-spacing: 1.5px;font-weight: 500;font-size: 14px;display: block;width:300px;word-wrap: break-word;"><?= $customer_data['address'] ?></span>
                            <span style="letter-spacing: 1.5px;position: relative;top: 3px;padding-bottom: 6px;font-weight: 500;font-size: 14px;">  <?= $customer_data['cityname'] ?>  </span>
                        </p>
                        <ul class="leftul">
                            <li>Contact No <span>:</span></li>
                            <li style="padding-left: 11px;"><?= $mobile ?></li>
                        </ul>
                        <ul class="leftul" style="padding-top: 3px;">
                            <li>User Id<span>:</span></li>
                            <li style="padding-left: 44px;"><?= $customer_data['actid'] ?></li>
                        </ul>
                    </div>
                </div>
                <div class="rightInvoice" style="width: 400px">
                    <div class="sentenceFull1">
                        <span>Invoice</span>
                    </div>
                    <table>
                        <tbody>
                        <tr>
                            <td>Account Id</td>
                            <td style="text-align: right;"><?= $customer_data['actid'] ?></td>
                        </tr>
                        <tr>
                            <td>Invoice No</td>
                            <td style="text-align: right;">17-18/<?= $request_result['id'] ?></td>
                        </tr>
                        <tr>
                            <td>Pay By Date</td>
                            <td style="text-align: right;"><?= $request_result['created_at'] ?></td>
                        </tr>
                        <tr>
                            <td>Invoice Period</td>
                            <td style="text-align: right;"><?= $todatedata  ?>
                                To
                                <?= $fromdatedata ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="clear:both;float:none;padding:1px;padding-bottom: 0px !important;">
                <hr style="color: blue;border: solid 1px;width: 96%;margin-left: 15px;">
            </div>
            <div class="sentenceFull">

            </div>
            <div class="tableFull">
                <table style="width: 100%;">
                    <thead>
                    <tr>
                        <th>Previous Balance</th>
                        <th></th>
                        <th>Current Charges</th>
                        <th></th>
                        <th>Total Amount</th>
                        <th>Due Date</th>
                        <th>After Due Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>0</td>
                        <td>+</td>
                        <td><?= $Amount ?></td>
                        <td>=</td>
                        <td><?= $Amount ?></td>
                        <td> <?= date_format(date_create($subscriptions['expirydt']), "Y-m-d") ?></td>
                        <td>N/A</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="clear:both;float:none;margin-top: -12px;">
                <hr style="color: blue;border: solid 1px;width: 96%;margin-left: 15px;">
            </div>
            <div class="sentenceFull">
                    <span><strong class="oddOut" style="color:#595959;font-size: 15px;padding-right: 9px;">Plan</strong>
                        <?= $request_arr['cpnTypeId'] ?>
                </span>
            </div>
            <div class="leftDown">
                <div class="ontent" style="width:7%;float:left;height: 100px;"></div>
                <div class="leftDownContent" style="width:93%;float:left">
                    <div class="ulTable">
                        <span style="color: #034ea2;font-size: 18px;font-weight: 500;line-height: 41px;margin: 10px 0;">Current Month Charges</span>
                        <div class="rightInvoice1">
                            <table class="table" style="width: 90%;padding-left: 18px;">
                                <tbody>
                                <tr>
                                    <td>Plan Charges</td>
                                    <td style="text-align: right;font-size: 14px;"><?= $request_result_plan['base'] ?></td>
                                </tr>
                                <tr>
                                    <td>Top up</td>
                                    <td style="text-align: right;font-size: 14px;">0</td>
                                </tr>
                                <tr>
                                    <td>Static IP Charges</td>
                                    <td style="text-align: right;font-size: 14px;"></td>
                                </tr>
                                <tr>
                                    <td>Late Fee</td>
                                    <td style="text-align: right;font-size: 14px;">0</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td style="text-align: right;font-size: 14px;"><?= $request_result_plan['base'] ?></td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td style="text-align: right;font-size: 14px;">0</td>
                                </tr>
                                <tr>
                                    <td>Taxes</td>
                                    <td style="text-align: right;font-size: 14px;">0</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 12px;font-size: 13px;">CGST(9%)</td>
                                    <td style="font-size: 13px;text-align: right;"><?= $request_result_plan['CGST'] ?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 12px;font-size: 13px;">SGST(9%)</td>
                                    <td style="font-size: 13px;text-align: right;"><?= $request_result_plan['SGST'] ?></td>
                                </tr>
                                <tr>
                                    <td class="colorVAlueX" style="padding-top:12px;">Total Payable</td>
                                    <td class="colorVAlueX"
                                        style="text-align: right;font-size: 14px;padding-top: 8px;"><?= $request_result_plan['price_inc_of_tax'] ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--  <span style="padding-top: 21px;padding-bottom: 3px;">34</span> -->
            <div class="rightDown">
                <div class="ontent" style="width:10%;float:left;height: 100px;"></div>
                <div style="width:90%;float:left">
                    <img src="./images/offer/offer.jpg" style="width: 350px;height:350px;">
                    <!-- <div style="width:50%;float:left">
                    <img src="click_here.jpg" style="padding-left: 20px;">
                </div>
                <div style="float:none;width:50%;float:left;">
                    <p>Click here to
                        <br>PAY NOW</p>
                </div> -->
                </div>
            </div>
            <div style="clear:both;float:none;padding: 5px;">
                <!--  <hr style="color: blue;border: solid 1px;width: 96%;margin-left: 15px;"> -->
            </div>
            <div class="termsContent" style="margin: 28px;width: 850px; margin-top:8px;margin-bottom: 17px;">
                <div class="termLine" style="overflow: auto;border: 1px solid #000;">
                    <div class="left" style="width:440px;">
                        <div class="leftSpace">
                            <span> Terms and Conditions* :</span>
                            <p>1. Kindly make renewal on or before expiry date to avoid disconnection.</p>
                            <p>2. This is Computer Generated Invoice and does not require any signature.</p>
                            <p>3. For any queries, please call us on 9448061634.</p>
                            <p></p>
                            <p></p>
                            <p></p>
                            <p></p>
                        </div>
                    </div>
                    <div class="rightInvoice" style="width:400px; width:400px;border-left: 1px solid #000;">
                        <div class="rightSpace" style="padding-left: 18px;">
                            <span>Franchise</span>
                            <p style="margin-top: 0px;">Sri Lakshmi Networks</p>
                            <p>60-2,Ananthanagar Layout, First Phase, First Left Cross, Krishnappa Building, Behind
                                Canara Bank ATM, Bengaluru 560100</p>
                            <p>Contact: 08049587000</p>
                        </div>
                        <hr>
                        <div class="rightSpace" style="padding-left: 18px;">
                            <!-- <img src="" alt="Fibronet" style="width: 189px;"> -->
                            <span>Fibronet</span>
                            <p>#13, 9th Cross, New Bank Colony, Chunchagatta Main Road, Konanakunte, Bangalore -
                                560062.</p>
                            <p>Goods and Services Tax Number: 29AACCN1202G1ZX </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sentenceFull ">
                <p style="text-align:center; ">Pay your broadband payment in <a>https://www.srilakshminetworks.com</a>
                </p>
            </div>
        </div>

    </div>
    </div>
    <div id="mydiv" style="display: none;">
        <!-- <img src="img/spinner.gif" class="ajax-loader" /> -->
    </div>

    <script>
        $(document).ready(function () {
            var _originalSize = $(window).width() + $(window).height()
            $(window).resize(function () {
                if ($(window).width() + $(window).height() != _originalSize) {
                    console.log("keyboard show up");
                    $(".login_footer").css("position", "relative");
                } else {
                    console.log("keyboard closed");
                    $(".login_footer").css("position", "fixed");
                }
            });
        });
    </script>
    </body>
    <script>
        function inject() {

            var originalOpenWndFnKey = "originalOpenFunction";

            var originalWindowOpenFn = window.open,
                originalCreateElementFn = document.createElement,
                originalCreateEventFn = document.createEvent,
                windowsWithNames = {};
            var timeSinceCreateAElement = 0;
            var lastCreatedAElement = null;
            var fullScreenOpenTime;
            var parentOrigin = (window.location != window.parent.location) ? document.referrer : document.location;

            window[originalOpenWndFnKey] = window.open; // save the original open window as global param

            function newWindowOpenFn() {

                var openWndArguments = arguments,
                    useOriginalOpenWnd = true,
                    generatedWindow = null;

                function blockedWndNotification(openWndArguments) {
                    parent.postMessage({
                        type: "blockedWindow",
                        args: JSON.stringify(openWndArguments)
                    }, parentOrigin);
                }

                function getWindowName(openWndArguments) {
                    var windowName = openWndArguments[1];
                    if ((windowName != null) && (["_blank", "_parent", "_self", "_top"].indexOf(windowName) < 0)) {
                        return windowName;
                    }

                    return null;
                }

                function copyMissingProperties(src, dest) {
                    var prop;
                    for (prop in src) {
                        try {
                            if (dest[prop] === undefined) {
                                dest[prop] = src[prop];
                            }
                        } catch (e) {
                        }
                    }
                    return dest;
                }

                // the element who registered to the event
                var capturingElement = null;
                if (window.event != null) {
                    capturingElement = window.event.currentTarget;
                }

                if (capturingElement == null) {
                    var caller = openWndArguments.callee;
                    while ((caller.arguments != null) && (caller.arguments.callee.caller != null)) {
                        caller = caller.arguments.callee.caller;
                    }
                    if ((caller.arguments != null) && (caller.arguments.length > 0) && (caller.arguments[0].currentTarget != null)) {
                        capturingElement = caller.arguments[0].currentTarget;
                    }
                }

                /////////////////////////////////////////////////////////////////////////////////
                // Blocked if a click on background element occurred (<body> or document)
                /////////////////////////////////////////////////////////////////////////////////

                if ((capturingElement != null) && (
                        (capturingElement instanceof Window) ||
                        (capturingElement === document) ||
                        (
                            (capturingElement.URL != null) && (capturingElement.body != null)
                        ) ||
                        (
                            (capturingElement.nodeName != null) && (
                                (capturingElement.nodeName.toLowerCase() == "body") ||
                                (capturingElement.nodeName.toLowerCase() == "#document")
                            )
                        )
                    )) {
                    window.pbreason = "Blocked a new window opened with URL: " + openWndArguments[0] + " because it was triggered by the " + capturingElement.nodeName + " element";
                    // console.info(window.pbreason);
                    useOriginalOpenWnd = false;
                } else {
                    useOriginalOpenWnd = true;
                }
                /////////////////////////////////////////////////////////////////////////////////

                /////////////////////////////////////////////////////////////////////////////////
                // Block if a full screen was just initiated while opening this url.
                /////////////////////////////////////////////////////////////////////////////////

                // console.info("fullscreen: " + ((new Date()).getTime() - fullScreenOpenTime));
                // console.info("webkitFullscreenElement: " + document.webkitFullscreenElement);
                var fullScreenElement = document.webkitFullscreenElement || document.mozFullscreenElement || document.fullscreenElement
                if ((((new Date()).getTime() - fullScreenOpenTime) < 1000) || ((isNaN(fullScreenOpenTime) && (isDocumentInFullScreenMode())))) {

                    window.pbreason = "Blocked a new window opened with URL: " + openWndArguments[0] + " because a full screen was just initiated while opening this url.";
                    // console.info(window.pbreason);

                    /* JRA REMOVED
                     if (window[script_params.fullScreenFnKey]) {
                     window.clearTimeout(window[script_params.fullScreenFnKey]);
                     }
                     */

                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    }

                    useOriginalOpenWnd = false;
                }
                /////////////////////////////////////////////////////////////////////////////////

                if (useOriginalOpenWnd == true) {

                    // console.info("allowing new window to be opened with URL: " + openWndArguments[0]);

                    generatedWindow = originalWindowOpenFn.apply(this, openWndArguments);

                    // save the window by name, for latter use.
                    var windowName = getWindowName(openWndArguments);
                    if (windowName != null) {
                        windowsWithNames[windowName] = generatedWindow;
                    }

                    // 2nd line of defence: allow window to open but monitor carefully...

                    /////////////////////////////////////////////////////////////////////////////////
                    // Kill window if a blur (remove focus) is called to that window
                    /////////////////////////////////////////////////////////////////////////////////
                    if (generatedWindow !== window) {
                        var openTime = (new Date()).getTime();
                        var originalWndBlurFn = generatedWindow.blur;
                        generatedWindow.blur = function () {
                            if (((new Date()).getTime() - openTime) < 1000 /* one second */) {
                                window.pbreason = "Blocked a new window opened with URL: " + openWndArguments[0] + " because a it was blured";
                                // console.info(window.pbreason);
                                generatedWindow.close();
                                blockedWndNotification(openWndArguments);
                            } else {
                                // console.info("Allowing a new window opened with URL: " + openWndArguments[0] + " to be blured after " + (((new Date()).getTime() - openTime)) + " seconds");
                                originalWndBlurFn();
                            }
                        };
                    }
                    /////////////////////////////////////////////////////////////////////////////////

                } else { // (useOriginalOpenWnd == false)

                    var location = {
                        href: openWndArguments[0]
                    };
                    location.replace = function (url) {
                        location.href = url;
                    };

                    generatedWindow = {
                        close: function () {
                            return true;
                        },
                        test: function () {
                            return true;
                        },
                        blur: function () {
                            return true;
                        },
                        focus: function () {
                            return true;
                        },
                        showModelessDialog: function () {
                            return true;
                        },
                        showModalDialog: function () {
                            return true;
                        },
                        prompt: function () {
                            return true;
                        },
                        confirm: function () {
                            return true;
                        },
                        alert: function () {
                            return true;
                        },
                        moveTo: function () {
                            return true;
                        },
                        moveBy: function () {
                            return true;
                        },
                        resizeTo: function () {
                            return true;
                        },
                        resizeBy: function () {
                            return true;
                        },
                        scrollBy: function () {
                            return true;
                        },
                        scrollTo: function () {
                            return true;
                        },
                        getSelection: function () {
                            return true;
                        },
                        onunload: function () {
                            return true;
                        },
                        print: function () {
                            return true;
                        },
                        open: function () {
                            return this;
                        },
                        opener: window,
                        closed: false,
                        innerHeight: 480,
                        innerWidth: 640,
                        name: openWndArguments[1],
                        location: location,
                        document: {
                            location: location
                        }
                    };

                    copyMissingProperties(window, generatedWindow);

                    generatedWindow.window = generatedWindow;

                    var windowName = getWindowName(openWndArguments);
                    if (windowName != null) {
                        try {
                            // originalWindowOpenFn("", windowName).close();
                            windowsWithNames[windowName].close();
                            // console.info("Closed window with the following name: " + windowName);
                        } catch (err) {
                            // console.info("Couldn't close window with the following name: " + windowName);
                        }
                    }

                    setTimeout(function () {
                        var url;
                        if (!(generatedWindow.location instanceof Object)) {
                            url = generatedWindow.location;
                        } else if (!(generatedWindow.document.location instanceof Object)) {
                            url = generatedWindow.document.location;
                        } else if (location.href != null) {
                            url = location.href;
                        } else {
                            url = openWndArguments[0];
                        }
                        openWndArguments[0] = url;
                        blockedWndNotification(openWndArguments);
                    }, 100);
                }

                return generatedWindow;
            }

            /////////////////////////////////////////////////////////////////////////////////
            // Replace the window open method with Poper Blocker's
            /////////////////////////////////////////////////////////////////////////////////
            window.open = function () {
                try {
                    return newWindowOpenFn.apply(this, arguments);
                } catch (err) {
                    return null;
                }
            };
            /////////////////////////////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Monitor dynamic html element creation to prevent generating <a> elements with click dispatching event
            //////////////////////////////////////////////////////////////////////////////////////////////////////////
            document.createElement = function () {

                var newElement = originalCreateElementFn.apply(document, arguments);

                if (arguments[0] == "a" || arguments[0] == "A") {

                    timeSinceCreateAElement = (new Date).getTime();

                    var originalDispatchEventFn = newElement.dispatchEvent;

                    newElement.dispatchEvent = function (event) {
                        if (event.type != null && (("" + event.type).toLocaleLowerCase() == "click")) {
                            window.pbreason = "blocked due to an explicit dispatchEvent event with type 'click' on an 'a' tag";
                            // console.info(window.pbreason);
                            parent.postMessage({
                                type: "blockedWindow",
                                args: JSON.stringify({
                                    "0": newElement.href
                                })
                            }, parentOrigin);
                            return true;
                        }

                        return originalDispatchEventFn(event);
                    };

                    lastCreatedAElement = newElement;

                }

                return newElement;
            };
            /////////////////////////////////////////////////////////////////////////////////

            /////////////////////////////////////////////////////////////////////////////////
            // Block artificial mouse click on frashly created <a> elements
            /////////////////////////////////////////////////////////////////////////////////
            document.createEvent = function () {
                try {
                    if ((arguments[0].toLowerCase().indexOf("mouse") >= 0) && ((new Date).getTime() - timeSinceCreateAElement) <= 50) {
                        window.pbreason = "Blocked because 'a' element was recently created and " + arguments[0] + " event was created shortly after";
                        // console.info(window.pbreason);
                        arguments[0] = lastCreatedAElement.href;
                        parent.postMessage({
                            type: "blockedWindow",
                            args: JSON.stringify({
                                "0": lastCreatedAElement.href
                            })
                        }, parentOrigin);
                        return null;
                    }
                    return originalCreateEventFn.apply(document, arguments);
                } catch (err) {
                }
            };
            /////////////////////////////////////////////////////////////////////////////////

            /////////////////////////////////////////////////////////////////////////////////
            // Monitor full screen requests
            /////////////////////////////////////////////////////////////////////////////////
            function onFullScreen(isInFullScreenMode) {
                if (isInFullScreenMode) {
                    fullScreenOpenTime = (new Date()).getTime();
                    // console.info("fullScreenOpenTime = " + fullScreenOpenTime);
                } else {
                    fullScreenOpenTime = NaN;
                }
            };
            /////////////////////////////////////////////////////////////////////////////////

            function isDocumentInFullScreenMode() {
                // Note that the browser fullscreen (triggered by short keys) might
                // be considered different from content fullscreen when expecting a boolean
                return ((document.fullScreenElement && document.fullScreenElement !== null) || // alternative standard methods
                ((document.mozFullscreenElement != null) || (document.webkitFullscreenElement != null))); // current working methods
            }

            document.addEventListener("fullscreenchange", function () {
                onFullScreen(document.fullscreen);
            }, false);

            document.addEventListener("mozfullscreenchange", function () {
                onFullScreen(document.mozFullScreen);
            }, false);

            document.addEventListener("webkitfullscreenchange", function () {
                onFullScreen(document.webkitIsFullScreen);
            }, false);

        }
        inject()
    </script>
    </html>
<?php

$dateend = date_format(date_create($subscriptions['expirydt']), "Y-m-d");

$dd = '<!doctype html>
    <html>
    <head>
     <meta charset="utf-8">
        <style>
            body {
               font-family: roboto;
                color: #595959;
                margin: 0px;
                padding: 10px;
                border: 2px solid #333333;
                font-size: 10px;
            }
            .boxMain {
                width: 100%;
                margin: 0px auto;
                background: #fff;
            }
            .termLine {
               
            }
            .left {
                float: left;
            }
            .rightInvoice {
                float: left;
            }
            .leftContent {
                padding-left: 29px;
                margin: 0.6em;
            }
            .leftul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .leftul li {
                display: inline-block;
                font-weight: 500;
                font-size: 15px;
            }
            .rightInvoice table tr td {
                font-size: 13px;
                padding-bottom: 5px;
                font-weight: 500;
            }
            .rightInvoice1 table tr td {
                font-size: 15px;
                padding-bottom: 10px;
                font-weight: 500;
            }
            .sentenceFull {
                float: none;
                clear: both;
                padding-left: 35px;
                margin-bottom: 5px;
                margin-top: 5px;
            }
            .sentenceFull1 {
                margin-bottom: 20px;
                margin-top: 28px;
            }
            .sentenceFull span {
                margin-bottom: 14px;
                color: #034ea2;
                font-size: 1.5em;
                font-weight: 500;
            }
            .sentenceFull1 span {
                margin-bottom: 14px;
                color: #034ea2;
                margin-top: 4px;
                font-size: 1.5em;
                font-weight: 500;
            }
            .tableFull tbody:before {
                content: "-";
                line-height: 1em;
                color: transparent;
            }
            .tableFull tbody:after {
                content: "-";
                display: block;
                line-height: 1em;
                color: transparent;
            }
            .tableFull th {
                font-size: 12px;
            }
    
            .tableFull td {
                text-align: center;
                color: #034ea2;
                border-radius: 7px;
                font-size: 11px;
                font-weight: 600 !important;
            }
            .leftDown {
                width: 50%;
                float: left;
            }
            .leftDownContent {
                background: #f6f6f6;
                overflow: auto;
                padding-left: 0px;
            }
            .rightDown {
                width: 50%;
                float: left;
            }
            .ulTable {
                padding-left: 14px;
                margin: 0;
            }
            .charges li {
                font-size: 12px;
                padding-bottom: 10px;
                font-weight: 500;
            }
    
            .charges1 li {
                font-size: 12px;
                padding-bottom: 10px;
                text-align: right;
            }
            .termsContent span {
                color: #034ea2;
                font-size: 12px;
                font-weight: 500;
                line-height: 41px;
                margin: 10px 0px;
            }
    
            .termsContent p {
                font-size: 12px;
                font-weight: 500;
            }
    
            .leftSpace {
                padding-left: 18px;
            }
    
            .oddOut {
                color: #595959;
            }
    
            .rightInvoice hr {
                margin: 0px !important;
            }
        </style>
        <style type="text/css" media="print">
            @page {
                size: auto;
                margin: 0mm;
            }
        </style>
    </head>
    <body>
     <div class="boxMain">
            <div style="padding-top: 8px;">
                <div class="left">
                    <div class="leftContent">
                        <img src="./images/splogo.jpg" alt="logo" style="margin-top:9px;width: 150px;">
                        <p style="padding-top: 18px;"><b style="font-size: 19px;">' . $customer_data['fname'] . ',</b>
                            <br> <span style="letter-spacing: 1.5px;font-weight: 500;font-size: 10px;display: block;width:350px;word-wrap: break-word;">' . $customer_data['address'] . '</span>
                            <span style="letter-spacing: 1.5px;position: relative;top: 3px;padding-bottom: 6px;font-weight: 500;font-size: 12px;">' . $customer_data['cityname'] . '</span></p>
                        <ul class="leftul">
                            <li>Contact No <span>:</span></li>
                            <li style="padding-left: 11px;">' . $mobile . '</li>
                        </ul>
                        <ul class="leftul" style="padding-top: 3px;">
                            <li>User Id<span>:</span></li>
                            <li style="padding-left: 44px;">' . $customer_data['actid'] . '</li>
                        </ul>
                    </div>
                </div>
                <div class="rightInvoice">
                    <div class="sentenceFull1">
                        <span>Invoice</span>
                    </div>
                    <table >
                        <tbody>
                        <tr>
                            <td style="font-size: 12px;">Account Id :</td>
                            <td style="text-align: right;font-size: 12px;">' . $customer_data['actid'] . '</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">Invoice No :</td>
                            <td style="text-align: right;font-size: 12px;">17-18/' . $request_result['id'] . '</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">Pay By Date :</td>
                            <td style="text-align: right;font-size: 12px;">' . $request_result['created_at'] . '</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">Invoice Date :</td>
                            <td style="text-align: right;font-size: 12px;">' . date('Y-m-d') . '</td>
                        </tr>
                        <tr>
                            <td>Invoice Period :</td>
                            <td style="text-align: right;">' . $todatedata . ' To 
                            ' . $fromdatedata . '</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="clear: both;"></div>
                <div class="tableFull">
                    <table style="width: 100%;">
                        <thead>
                        <tr>
                            <th>Previous Balance</th>
                            <th></th>
                            <th>Current Charges</th>
                            <th></th>
                            <th>Total Amount</th>
                            <th>Due Date</th>
                            <th>After Due Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>0</td>
                            <td>+</td>
                            <td>' . $Amount . '</td>
                            <td>=</td>
                            <td>' . $Amount . '</td>
                            <td>' . $dateend . '</td>
                            <td>N/A</td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <div class="sentenceFull">
                        <span><strong class="oddOut" style="color:#595959;font-size: 15px;padding-right: 9px;">Plan</strong>
                            ' . $request_arr['cpnTypeId'] . '
                        </span>
                    </div>
                    <br>
                    <div class="leftDown">
                        <div class="leftDownContent">
                            <div class="ulTable">
                                <span style="color: #034ea2;font-size: 13px;font-weight: 500;line-height: 41px;margin: 10px 0;">Current Month Charges</span>
                                <div class="rightInvoice1">
                                    <table class="table" style="width: 90%;padding-left: 18px;">
                                        <tbody>
                                        <tr>
                                            <td style="text-align: left;font-size: 12px;">Plan Charges</td>
                                            <td style="text-align: right;font-size: 12px;">' . $request_result_plan['base'] . '</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;font-size: 12px;">Top up</td>
                                            <td style="text-align: right;font-size: 12px;">0</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;font-size: 12px;"> Static IP Charges</td>
                                            <td style="text-align: right;font-size: 12px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;font-size: 12px;">Late Fee</td>
                                            <td style="text-align: right;font-size: 12px;">0</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;font-size: 12px;">Total</td>
                                            <td style="text-align: right;font-size: 12px;">' . $request_result_plan['base'] . '</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;font-size: 12px;">Discount</td>
                                            <td style="text-align: right;font-size: 12px;">0</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;font-size: 12px;">Taxes</td>
                                            <td style="text-align: right;font-size: 12px;">0</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;padding-left: 12px;font-size: 12px;">CGST(9%) </td>
                                            <td style="font-size: 13px;text-align: right;">' . $request_result_plan['CGST'] . '</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;padding-left: 12px;font-size: 12px;">SGST(9%) </td>
                                            <td style="font-size: 13px;text-align: right;">' . $request_result_plan['SGST'] . '</td>
                                        </tr>
                                        <tr>
                                            <td class="colorVAlueX" style="text-align: leftpadding-top:12px;">Total Payable</td>
                                            <td class="colorVAlueX" style="text-align: right;font-size: 14px;padding-top: 8px;">' . $request_result_plan['price_inc_of_tax'] . '</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rightDown">
                            <img src="./images/offer/offer.jpg" style="margin-left: 50px; width:250px;height:250px;">
                    </div>
                    <div style="clear: both;"></div>
                    <br><br><br>
                    <div class="termLine" >
                        <div class="left" style="width:50%;">
                            <div class="leftSpace">
                                <span> Terms and Conditions* :</span>
                                <p>1. Kindly make renewal on or before expiry date to avoid disconnection.</p>
                                <p>2. This is Computer Generated Invoice and does not require any signature.</p>
                                <p>3. For any queries, please call us on 9448061634.</p>
                                <p></p>
                                <p> </p>
                                <p></p>
                                <p></p>
                            </div>
                        </div>
                        
                        <div class="rightInvoice" style="width:50%;">
                            <div class="rightSpace" style="padding-left: 18px;">
                                <span>Franchise</span>
                                <p style="margin-top: 0px;">Sri Lakshmi Networks</p>
                                <p>60-2,Ananthanagar Layout, First Phase, First Left Cross, Krishnappa Building, Behind Canara Bank ATM, Bengaluru 560100</p>
                                <p>Contact: 08049587000</p>
                            </div>
                            <hr>
                            <div class="rightSpace" style="padding-left: 18px;">
                                <span>Fibronet</span>
                                <p>#13, 9th Cross, New Bank Colony, Chunchagatta Main Road, Konanakunte, Bangalore - 560062.</p>
                                <p>Goods and Services Tax Number: 29AACCN1202G1ZX </p>
                            </div>
                        </div>
                    </div>
                    <div class="sentenceFull ">
                        <p style="text-align:center; ">Pay your broadband payment in <a>https://www.srilakshminetworks.com</a></p>
                    </div>
                </div>
                </div>
                </div></div>
    </div>
    </body>
    </html>';
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
$filename = dompdf($dd);
send_mail($message, $to, $filename);
$sql = "UPDATE tbl_transaction SET invoicelink='$filename' WHERE id=$order_id";
if (mysqli_query($con, $sql)) {
} else {
    var_dump(mysqli_error($con));
    die;
}
?>