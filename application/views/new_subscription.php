<?php include 'header.php'; ?>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<?php
if ($_GET['mobile_no_search']) {
    $con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");
// Check connection
    if ($_GET['mobile_no_search'] != '') {
        if (strlen($_GET['mobile_no_search']) == 10) {
            if (mysqli_connect_errno()) {
                die("no databae connection");
            }

            $query = mysqli_query($con, "SELECT * FROM  tbl_subscr_request WHERE mobile=" . $_GET['mobile_no_search']);
            $details = mysqli_fetch_all($query, MYSQLI_ASSOC);
            if (count($details) > 0) {
                $flag = true;
                $details = $details[0];
            }
        } else {
            echo "You Must enter Mobile No 10 Digit!";
        }

    } else {
        echo "You Must enter Mobile No";
    }

}

if ($_POST['Sr_no']) {

    if ($_POST['Sr_no'] != '') {
        $order_id = $_POST['Sr_no'];
        $con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");
        $sql = "";
        $sql = "";
        $sql .= "insert into tbl_subscription_details (";
        $sql .= "applicant_name,";
        $sql .= "contact_person,";
        $sql .= "designation,";
        $sql .= "dob,";
        $sql .= "marital_status,";
        $sql .= "fathers_husband_name,";
        $sql .= "nationality,";
        $sql .= "recent_bill_attached,";
        $sql .= "con_addr1,";
        $sql .= "con_city,";
        $sql .= "con_state,";
        $sql .= "con_pincode,";
        $sql .= "con_contact_no,";
        $sql .= "con_emailid,";
        $sql .= "ncdr_Is_adv_paid,";
        $sql .= "ncdr_prefered_date,";
        $sql .= "ncdr_time,";
        $sql .= "ncdr_availability,";
        $sql .= "ncdr_concealed_wiring,";
        $sql .= "ncdr_approx_distance,";
        $sql .= "ncdr_Iocation,";
        $sql .= "serv_home_ofc,";
        $sql .= "serv_bandwidth,";
        $sql .= "serv_port_speed,";
        $sql .= "serv_account_user_id_1,";
        $sql .= "serv_account_user_id_2,";
        $sql .= "serv_account_user_id_3,";
        $sql .= "serv_no_static_ip,";
        $sql .= "pan_no";
        $sql .= ") values (";
        $sql .= "'" . $_POST['applicant_name'] . "' ";
        $sql .= ",'" . $_POST['contact_name'] . "' ";
        $sql .= ",'" . $_POST['applicant_designation'] . "' ";
        $sql .= ",'" . $_POST['applicant_dob'] . "' ";
        $sql .= ",'" . $_POST['applicant_marital_status'] . "' ";
        $sql .= ",'" . $_POST['applicant_father_name'] . "' ";
        $sql .= ",'" . $_POST['applicant_nationality'] . "' ";
        $sql .= ",'" . $_POST['applicant_recent_bill'] . "' ";
        $sql .= ",'" . $_POST['conn_address'] . "' ";
        $sql .= ",'" . $_POST['conn_city'] . "' ";
        $sql .= ",'" . $_POST['conn_state'] . "' ";
        $sql .= ",'" . $_POST['conn_pincode'] . "' ";
        $sql .= ",'" . $_POST['conn_mobile'] . "' ";
        $sql .= ",'" . $_POST['conn_email'] . "' ";
        $sql .= ",'" . $_POST['advance_booking'] . "' ";
        $sql .= ",'" . $_POST['preferred_date'] . "' ";
        $sql .= ",'" . $_POST['preferred_time'] . "' ";
        $sql .= ",'" . $_POST['net_avai_mf'] . "' ";
        $sql .= ",'" . $_POST['net_wire_req'] . "' ";
        $sql .= ",'" . $_POST['net_app_dis'] . "' ";
        $sql .= ",'" . $_POST['net_conn_loc'] . "' ";
        $sql .= ",'" . $_POST['services_usage_type'] . "' ";
        $sql .= ",'" . $_POST['services_usage_month'] . "' ";
        $sql .= ",'" . $_POST['services_port_speed'] . "' ";
        $sql .= ",'" . $_POST['services_user_id_1'] . "' ";
        $sql .= ",'" . $_POST['services_user_id_2'] . "' ";
        $sql .= ",'" . $_POST['services_user_id_3'] . "' ";
        $sql .= ",'" . $_POST['services_static_ip'] . "'";
        $sql .= ",'" . $_POST['applicant_panno'] . "') ";
        $query = mysqli_query($con, $sql);
        if ($query > 0) {
            echo "Connection Saved Successfully";
            $sql_update = "UPDATE tbl_subscr_request SET status='Active' WHERE Sr_no = $order_id";
            $query = mysqli_query($con, $sql_update);
        } else {
            echo "Something with the info Please check and try again !";
        }
    } else {
        echo "Please fetch the details first from form";
    }
}
?>
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
        <section class="content-main">
            <!-- **Container** -->
            <div class="container">
                <!-- **Primary** -->
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="hr-border-title"><span>New Subscription Form</span></h2>
                        <div class="panel panel-default">
                            <div class="panel-heading text-center" style="background-color: #900723; color: white;">New
                                Subscription Form
                            </div>
                            <div class="panel-body">
                                <form class="form-horizontal" action="new_subscription.php" method="post">
                                    <div class="form-group">
                                        <label for="contact_name" class="col-sm-5 control-label">Mobile No</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" name="mobile_no_search"
                                                   id="mobile_no_search" placeholder="Mobile No" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-5 col-sm-7">
                                            <button type="submit" class="btn btn-default pull-left">Submit</button>
                                        </div>
                                    </div>
                                </form>
                                <form class="form-horizontal" action="new_subscription.php" method="post">
                                    <div class="form-group">
                                        <label for="applicant_name" class="col-sm-2 control-label">Applicant's
                                            name</label>
                                        <div class="col-sm-2" style="width: max-content;">
                                            <label class="radio-inline">
                                                <input type="radio" name="applicant_gender" value="male"
                                                       checked="checked"> Mr.
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="applicant_gender" value="femele"> Ms.
                                            </label>
                                        </div>
                                        <?php
                                        if (isset($details['Sr_no'])) {
                                            ?>
                                            <input type="hidden" class="form-control"
                                                   id="Sr_no" value="<?php echo $details['Sr_no'] ?>"
                                                   name="Sr_no">
                                            <?php
                                        }
                                        ?>
                                        <div class="col-sm-8" style="max-width: 64.66666667%;">
                                            <input type="text" class="form-control"
                                                   id="applicant_name"
                                                   value="<?php echo $details['f_name'] . ' ' . $details['l_name']; ?>"
                                                   name="applicant_name" placeholder="Firstname Middlename Lastname"
                                                   required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_name" class="col-sm-2 control-label">Contact
                                            Person</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="contact_name"
                                                   id="contact_name" placeholder="Contact Person Name"
                                                   value="<?php echo $details['contact_person'] ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="applicant_designation"
                                               class="col-sm-2 control-label">Designation</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="applicant_designation"
                                                   id="applicant_designation" placeholder="Designation"
                                                   value="<?php echo $details['designation'] ?>"
                                                   required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="applicant_dob" class="col-sm-4 control-label">Date Of
                                                    Birth</label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control" name="applicant_dob"
                                                           id="applicant_dob" placeholder="Date Of Birth"
                                                           value="<?php echo $details['dob'] ?>"
                                                           required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="applicant_panno" class="col-sm-2 control-label">Pan
                                                    No</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="applicant_panno"
                                                           id="applicant_panno" placeholder="Pan No"
                                                           value=""
                                                           required="required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="applicant_marital_status" class="col-sm-2 control-label">Marital
                                            Status</label>
                                        <div class="col-sm-2" style="width: max-content;">
                                            <label class="radio-inline">
                                                <input type="radio" name="applicant_marital_status" value="Married"
                                                       checked="checked"> Married
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="applicant_marital_status" value="Single">
                                                Single
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <label for="applicant_father_name" class="col-sm-3 control-label">Father/Husband's
                                                    Name</label>
                                                <div class="col-md-9" style="max-width:     width: 72%;">
                                                    <input type="text" class="form-control"
                                                           id="applicant_father_name"
                                                           value="<?php echo $details['fathers_husband_name'] ?>"
                                                           placeholder="Father/Husband's Name" required="required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="applicant_nationality" class="col-sm-4 control-label">Nationality</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control"
                                                           name="applicant_nationality"
                                                           value="<?php echo $details['nationality'] ?>"
                                                           id="applicant_nationality" placeholder="Nationality"
                                                           required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-1"></div>
                                                <div class="col-sm-10">
                                                    <label for="">Existing/Old Service Provider</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="applicant_recent_bill" class="col-sm-4 control-label">Recent
                                                    bill attached</label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="applicant_recent_bill" value="1"
                                                               checked="checked"> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="applicant_recent_bill" value="0">
                                                        No.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center"
                                                     style="background-color: #900723; color: white;">Connectivity
                                                    Address
                                                </div>
                                                <div class="panel-body conn_dtl">
                                                    <div class="form-group">
                                                        <label for="conn_address"
                                                               class="col-sm-2 control-label">Address</label>
                                                        <div class="col-sm-10">
                                                                 <textarea class="form-control" id="conn_address"
                                                                           name="conn_address"
                                                                           required="required"><?php echo $details['addr1'] ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <label for="conn_city"
                                                                       class="col-sm-4 control-label">City</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                           name="conn_city" id="conn_city"
                                                                           value="<?php echo $details['city'] ?>"
                                                                           placeholder="City"
                                                                           required="required">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <label for="conn_state"
                                                                       class="col-sm-3 control-label">State</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                           name="conn_state" id="conn_state"
                                                                           value="<?php echo $details['state'] ?>"
                                                                           placeholder="State" required="required">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="conn_pincode" class="col-sm-2 control-label">Pin
                                                            Code</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control"
                                                                   name="conn_pincode"
                                                                   value="<?php echo $details['pincode'] ?>"
                                                                   maxlength="6" minlength="6"
                                                                   id="conn_pincode" placeholder="Pincode">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="conn_mobile" class="col-sm-2 control-label">Mobile
                                                            No</label>
                                                        <div class="col-sm-10">
                                                            <input type="tel" class="form-control"
                                                                   name="conn_mobile"
                                                                   value="<?php echo $details['mobile'] ?>"
                                                                   id="conn_mobile" placeholder="Mobile No"
                                                                   required="required">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="conn_email" class="col-sm-2 control-label">Email
                                                            ID</label>
                                                        <div class="col-sm-10">
                                                            <input type="email" class="form-control"
                                                                   name="conn_email"
                                                                   value="<?php echo $details['email'] ?>"
                                                                   id="conn_email" placeholder="Email ID"
                                                                   required="required">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center"
                                                     style="background-color: #900723; color: white;">Billing
                                                    Address
                                                </div>
                                                <div class="panel-body bill_dtl">
                                                    <div class="checkbox">
                                                        <input type="checkbox" name="" id="copyAddress"> Same as
                                                        Connection Details
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="bill_address"
                                                               class="col-sm-2 control-label">Address</label>
                                                        <div class="col-sm-10">
                                                            <textarea class="form-control" id="bill_address"
                                                                      name="bill_address"
                                                                      required="required"><?php echo $details['addr1'] ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <label for="bill_city"
                                                                       class="col-sm-4 control-label">City</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                           name="bill_city" id="bill_city"
                                                                           value="<?php echo $details['city'] ?>"
                                                                           placeholder="City" required="required">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <label for="bill_state"
                                                                       class="col-sm-3 control-label">State</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                           value="<?php echo $details['state'] ?>"
                                                                           name="bill_state" id="bill_state"
                                                                           placeholder="State" required="required">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="bill_pincode" class="col-sm-2 control-label">Pin
                                                            Code</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control"
                                                                   name="bill_pincode"
                                                                   value="<?php echo $details['pincode'] ?>"
                                                                   maxlength="6" minlength="6" id="bill_pincode"
                                                                   placeholder="Pincode">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="bill_mobile" class="col-sm-2 control-label">Mobile
                                                            No</label>
                                                        <div class="col-sm-10">
                                                            <input type="tel" class="form-control"
                                                                   name="bill_mobile"
                                                                   value="<?php echo $details['mobile'] ?>"
                                                                   id="bill_mobile" placeholder="Mobile No"
                                                                   required="required">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="bill_email" class="col-sm-2 control-label">Email
                                                            ID</label>
                                                        <div class="col-sm-10">
                                                            <input type="email" class="form-control"
                                                                   name="bill_email"
                                                                   value="<?php echo $details['email'] ?>"
                                                                   id="bill_email" placeholder="Email ID"
                                                                   required="required">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="advance_booking" class="col-sm-7 control-label">Is It
                                                Advance
                                                Booking ?</label>
                                            <div class="col-sm-5">
                                                <label class="radio-inline">
                                                    <input type="radio" name="advance_booking" value="1"
                                                           checked="checked"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="advance_booking" value="0"> No.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="preferred_date" class="col-md-4 control-label">Preffered
                                                Date</label>
                                            <div class="col-md-8">
                                                <input type="date" name="preferred_date" id="preferred_date"
                                                       value="<?php echo $details['ncdr_prefered_date'] ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="preferred_time" class="col-sm-4 control-label">Time
                                                :</label>
                                            <div class="col-sm-8">
                                                <label class="radio-inline">
                                                    <input type="radio" name="preferred_time" value="1"
                                                           checked="checked"> 1st Half
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="preferred_time" value="0">2nd Half
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="panel panel-default">
                                        <div class="panel-heading text-center"
                                             style="background-color: #900723; color: white;">Network Connectivity
                                            Data
                                            Requirement
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                        <label for="net_avai_mf" class="col-sm-4 control-label">Availability
                                                            Mon - Fri</label>
                                                        <div class="col-sm-8">
                                                            <input type="time" class="form-control"
                                                                   name="net_avai_mf"
                                                                   value="<?php echo $details['ncdr_time'] ?>"
                                                                   id="net_avai_mf" placeholder="Tome : AM/PM"
                                                                   required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="net_avai_ss" class="col-sm-4 control-label">Sat
                                                            -
                                                            Sun</label>
                                                        <div class="col-sm-8">
                                                            <input type="time" class="form-control"
                                                                   name="net_avai_ss"
                                                                   value="<?php echo $details['ncdr_availability'] ?>"

                                                                   id="net_avai_ss" placeholder="Tome : AM/PM"
                                                                   required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="net_wire_req" class="col-md-6 control-label">Concealed
                                                            Wirind Required</label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="net_wire_req" value="1"
                                                                   checked="checked"> Yes
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="net_wire_req" value="0"> No
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="net_app_dis" class="col-sm-4 control-label">Approximate
                                                            Distance</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" class="form-control"
                                                                   name="net_app_dis"
                                                                   value="<?php echo $details['ncdr_approx_distance'] ?>"

                                                                   id="net_app_dis"
                                                                   placeholder="Approximate Distance">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-8">
                                                        <label for="net_conn_loc" class="col-md-3 control-label">Connectivity
                                                            Location</label>

                                                        <label class="checkbox-inline col-md-2 col-xs-12">
                                                            <input type="checkbox" name="net_conn_loc"
                                                                   value="Bed Room"
                                                                   checked="checked"> Bed Room
                                                        </label>
                                                        <label class="checkbox-inline col-md-2 col-xs-12">
                                                            <input type="checkbox" name="net_conn_loc"
                                                                   value="Drawing Room">Drawing Room
                                                        </label>
                                                        <label class="checkbox-inline col-md-2 col-xs-12">
                                                            <input type="checkbox" name="net_conn_loc"
                                                                   value="Living Room">Living Room
                                                        </label>
                                                        <label class="checkbox-inline col-md-2 col-xs-12">
                                                            <input type="checkbox" name="net_conn_loc"
                                                                   value="Study Room">Study Room
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="net_conn_loc"
                                                               class="col-sm-4 control-label">Other</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control"
                                                                   name="net_conn_loc"
                                                                   id="net_conn_loc">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading text-center"
                                             style="background-color: #900723; color: white;">Services
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label class="radio-inline"><input type="radio"
                                                                                       name="services_loc_type"
                                                                                       value="1" checked="checked">
                                                        Home</label>
                                                    <label class="radio-inline"><input type="radio"
                                                                                       name="services_loc_type"
                                                                                       value="0">
                                                        Corporation</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="radio-inline"><input type="radio"
                                                                                       name="services_usage_type"
                                                                                       value="1" checked="checked">
                                                        Usage Based</label>
                                                    <label class="radio-inline"><input type="radio"
                                                                                       name="services_usage_type"
                                                                                       value="0"> Unlimited</label>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="services_port_speed" class="col-sm-6 control-label">Port
                                                        Speed</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control"
                                                               value="<?php echo $details['serv_port_speed'] ?>"
                                                               name="services_port_speed" id="services_port_speed"
                                                               placeholder="Port Speed" required="required">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="services_usage_month"
                                                           class="col-sm-6 control-label">Proposed
                                                        Usage / Month</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control"
                                                               value="<?php echo $details['serv_port_speed'] ?>"

                                                               name="services_usage_month" id="services_usage_month"
                                                               placeholder="Usage Month" required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="services_user_id_1" class="col-sm-2 control-label">Account
                                                    /
                                                    User ID</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control"
                                                           name="services_user_id_1"
                                                           value="<?php echo $details['serv_account_user_id_1'] ?>"
                                                           id="services_user_id_1" placeholder="1."
                                                           required="required">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control"
                                                           name="services_user_id_2"
                                                           value="<?php echo $details['serv_account_user_id_2'] ?>"
                                                           id="services_user_id_2" placeholder="2.">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control"
                                                           name="services_user_id_3"
                                                           value="<?php echo $details['serv_account_user_id_3'] ?>"
                                                           id="services_user_id_3" placeholder="3.">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control"
                                                           name="services_user_id_4"
                                                           id="services_user_id_4" placeholder="4.">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="services_static_ip" class="control-label col-md-6">No
                                                        Of
                                                        Static Ips</label>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control"
                                                               value="<?php echo $details['serv_no_static_ip'] ?>"
                                                               name="services_static_ip" id="services_static_ip"
                                                               placeholder="">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-default">Submit</button>
                                        </div>
                                    </div>


                                </form>
                                <?php
                                ?>


                            </div>

                        </div>

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

    <script>
        $(document).ready(function () {
        });
    </script>

<?php include 'footer.php' ?>