<?php include 'header.php';?>

<?php

$con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");

// Check connection
if (mysqli_connect_errno()) {
    die("no databae connection");
}
$query = mysqli_query($con, "SELECT * FROM tbl_plans");
$plans = mysqli_fetch_all($query, MYSQLI_ASSOC);
$error = "";
if (!empty($_POST)) {
    $f_name = @$_POST['firstname'];
    $l_name = @$_POST['lastname'];
    $addr1 = @$_POST['address1'];
    $addr2 = @$_POST['address2'];
    $city = @$_POST['city'];
    $state = @$_POST['state'];
    $pincode = @$_POST['pincode'];
    $plan = @$_POST['plan'];
    $conn_home_offce = @$_POST['location'];
    $mobile = @$_POST['mobile'];
    $email = @$_POST['email'];
    $agree = @$_POST['agree'];

    if (!empty($f_name) && !empty($l_name) && !empty($addr1) && !empty($addr2) && !empty($city) && !empty($state) && !empty($pincode) && !empty($plan) && !empty($conn_home_offce) && !empty($mobile) && !empty($email) && !empty($agree) && !empty($_FILES['address_proof']['name']) && !empty($_FILES['id_proof']['name']) && !empty($_FILES['pancard']['name'])) {
        $sql = "INSERT INTO tbl_subscr_request (f_name,l_name,addr1,addr2,city,state,pincode,plan,conn_home_offce,mobile,email,agree,status) VALUES ('$f_name','$l_name','$addr1','$addr2','$city','$state','$pincode','$plan','$conn_home_offce','$mobile','$email','$agree','new')";

        if (mysqli_query($con, $sql)) {
            $last_id = mysqli_insert_id($con);

            $ap = file_upload($_FILES['address_proof'], $last_id, 'address_proof');
            $ip = file_upload($_FILES['id_proof'], $last_id, 'id_proof');
            $pc = file_upload($_FILES['pancard'], $last_id, 'pancard');

            if ($ap && $ip && $pc) {
                $sql = "UPDATE tbl_subscr_request SET doc_addr_proof='$ap', doc_id_proof = '$ip', doc_pancare = '$pc' WHERE Sr_no=$last_id";

                if (mysqli_query($con, $sql)) {
                    echo "<script>alert('Request Submited Successfully');</script>";
                } else {
                    $error = "Some Internal Error update";

                }
            } else {
                $error = "File Upload Error";
            }

        } else {
            $error = "Some Internal Error update";
        }

        mysqli_close($con);

    } else {
        $error = "Some Values Are Missing";
    }

}

function file_upload($file, $last_id, $type) {
    $temp = explode(".", $file["name"]);
    $extension = end($temp);
    $newfilename = $last_id . "_" . $type . "." . $extension;
    if (move_uploaded_file($file["tmp_name"], "./upload/documents/" . $newfilename)) {
        return $newfilename;
    } else {
        return false;
    }
}
?>

    <style>
        .newconn-frm
        {

        }
        .newconn-frm input,.newconn-frm select{
            /*        background: transparent;*/
        }
        .newconn-frm select::option{
            /*color: yellow;*/
        }
        .newconn-frm input::placeholder{
            /*color: yellow;*/
        }
        .newconn-frm input{
            /*color: yellow;*/
        }

        .newconn-frm select{
        /*color: yellow;*/
        option{
            color: black !important;
        }
        }
        input[type="checkbox"]{
            width: 2.3em; /*Desired width*/
            height: 2.3em; /*Desired height*/
            outline: 1px solid transparent;
        }

        input[type="file"]{
            outline: 1px solid transparent;
        }

        input[type="checkbox"].error, input[type="number"].error, input[type="file"].error ,select.error {
            border-color: #ff0000 !important;
            outline-color: #ff0000 !important;
        }

        input[type="checkbox"], .checkbox-label, .file-label{
            margin-top: 5%;
        }

    </style>


    <!-- **Main** -->
    <div id="main">
        <!-- **Breadcrumb Section** -->
        <div class="breadcrumb-section">
            <div class="container">
                <h1>New Subscription</h1>
                <div class="breadcrumb">
                    <a href="index.php" title="">Home</a><span>/</span>
                </div>
            </div>
        </div>
        <!-- **Breadcrumb Section Ends** -->

        <!-- **Content Main** -->
        <section class="content-main">
            <!-- **Container** -->
            <div class="container">
                <!-- **Primary** -->

                <div class="row">
                    <div class="col-md-12">
                        <h2 class="hr-border-title"><span>New Subscription Form</span></h2>

                        <form method="post" action="new_request.php" class="newconn-frm" name="frmnewconn" style="max-width: 40%;" enctype="multipart/form-data">
                            <div class="clear"></div>
                            <span style="color: red;"><?=@$error;?></span>
                            <div class="clear"></div>
                            <div class="dt-sc-one-half column first">
                                <input type="text" name="firstname" placeholder="Your First Name*" required>
                            </div>
                            <div class="dt-sc-one-half column">
                                <input type="text" name="lastname" placeholder="Your Last Name*" required>
                            </div>
                            <div class="clear"></div>
                            <input type="text" placeholder="Address 1" name="address1" required>
                            <input type="text" placeholder="Address 2"  name="address2" required>

                            <div class="dt-sc-one-third column first">
                                <input type="text" name="city" placeholder="City" required>
                            </div>
                            <div class="dt-sc-one-third column">
                                <input type="text" name="state" value="Karnataka" placeholder="State" required>
                            </div>
                            <div class="dt-sc-one-third column">
                                <input type="text" name="pincode" placeholder="Pincode" pattern="\d{6}" title="6 Digit Pincode" required>
                            </div>

                            <div class="dt-sc-one-half column first">
                                <select name="plan" required="required">
                                    <option value="">Select Plan</option>
                                    <?php foreach ($plans as $key => $plan) {
                                        $plan_name_mod2 = str_replace('_', ' ', substr($plan['coupon_type_id'], 1)); ?>
                                        <option value="<?=$plan['Sr_no']?>"><?=$plan_name_mod2 . ' - ' . $plan['validity']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="dt-sc-one-half column">
                                <select name="location" required="required">
                                    <option value="">Home/Office</option>
                                    <option value="Home">Home</option>
                                    <option value="Office">Office</option>
                                </select>
                            </div>

                            <div class="dt-sc-one-half column first">
                                <input type="text" name="mobile" placeholder="Your Mobile No.*" minlength="10" maxlength="10" pattern="\d{10}" required>
                            </div>
                            <div class="dt-sc-one-half column">
                                <input type="email" name="email" placeholder="Your Email Address*" required>
                            </div>


                            <div class="dt-sc-one-half first column file-label">
                                Address Proof
                            </div>
                            <div class="dt-sc-one-half column file-label">
                                <input type="file" accept="image/*" name="address_proof" required="required">
                            </div>

                            <div class="dt-sc-one-half first column file-label">
                                ID Proof
                            </div>
                            <div class="dt-sc-one-half column file-label">
                                <input type="file" accept="image/*" name="id_proof" required="required">
                            </div>

                            <div class="dt-sc-one-half first column file-label">
                                Pancard
                            </div>
                            <div class="dt-sc-one-half column file-label">
                                <input type="file" accept="image/*" name="pancard" required>
                            </div>


                            <span style="display: inline-flex;">
                                <input type="checkbox" name="agree"  value="1" required="required"> <span class="checkbox-label">I Agree (Terms &amp; Condition)</span>
                                </span>

                            <div class="clear"></div>
                            <button type="submit" name="btnsend" value="submit" class="dt-sc-button small">Submit</button>
                            <div class="clear"></div>
                        </form>
                        <br><br>
                    </div>
                    <!-- **Primary Ends** -->
                </div>
                <!-- **Container Ends** -->
        </section>
        <!-- **Content Main Ends** -->

        <div class="tweet-box">
            <div class="container">
                <div id="tweets_container"></div>
            </div>
        </div>

    </div>
    <!-- **Main Ends** -->

<?php include 'footer.php'?>