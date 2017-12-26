<?php
include 'header.php'; ?>

<?php
require_once('db_config.php');

$customer_data = [];
if (isset($_POST['btnmobile'])) {
    require_once 'NetworkAPI.php';
    $mobile = $_POST['mobileno'];
    //$act_id =$_POST['act_id'];

    $error = "";
    if ($mobile != "" && strlen($mobile) == 10) {
        $network = new NetworkAPI();
        $customer_data = $network->getAccountDetailsByMobileNumber($mobile);
        if (count($customer_data) == 0) {
            $error = "No Account found with this Mobile No.";
            $customer_data = "";
        } else {
            $customer_data = $customer_data[0];
            $subscriptions_arr = $network->getSubscriptions($customer_data['actid']);
            if (count($customer_data) == 0) {
                $error = "No subscriptions found with this Mobile No.";
                $customer_data = "";
            } else {
                $subscriptions = reset($subscriptions_arr);
                $query = mysqli_query($con, "SELECT * FROM tbl_plans");
                $plans = mysqli_fetch_all($query, MYSQLI_ASSOC);

                // get current Plan
                $current_plan = $subscriptions['svcdescr'];
                $current_expiry = strtotime(date_format(date_create($subscriptions['expirydt']), "Y-m-d"));

                $plan_query = mysqli_query($con, "SELECT * FROM tbl_plans WHERE coupon_type_id = '$current_plan'");
                $current_plan_row = mysqli_fetch_assoc($plan_query);

                $plan_name_mod = str_replace('_', ' ', substr($current_plan_row['coupon_type_id'], 1));
                $plan_val_months = explode(' ', $current_plan_row['validity'])[0];

                $new_exp = date("Y-m-d", strtotime("+" . $plan_val_months . " month", $current_expiry));
                $amount = floor($current_plan_row['price_inc_of_tax']);
                $round_off = $current_plan_row['price_inc_of_tax'] - $amount;
                // $current_plan_data =
            }
        }
    } else {

        $error = "Please Enter Valid Mobile No";
    }
}

if (isset($_POST['btnaccountid'])) {
    require_once 'NetworkAPI.php';
    $act_id = $_POST['act_id'];

    $error = "";

    if ($act_id != "") {
        $network = new NetworkAPI();

        $customer_data = $network->getAccountDetails($act_id);
        //$customer_data = $network->getAccountDetailsByMobileNumber($mobile);

        if (count($customer_data) <= 2) {
            $aerror = "No Account found with this Account ID";
            $customer_data = "";
        } else {
            $act_id;
            $customer_data = $customer_data;
            $mobile = $customer_data['mobileno'];
            $subscriptions_arr = $network->getSubscriptions($customer_data['actid']);
            if (count($customer_data) == 0) {
                $aerror = "No subscriptions found with this Account ID";
                $customer_data = "";
            } else {
                $subscriptions = reset($subscriptions_arr);
                $query = mysqli_query($con, "SELECT * FROM tbl_plans");
                $plans = mysqli_fetch_all($query, MYSQLI_ASSOC);

                // get current Plan
                $current_plan = $subscriptions['svcdescr'];
                $current_expiry = strtotime(date_format(date_create($subscriptions['expirydt']), "Y-m-d"));

                $plan_query = mysqli_query($con, "SELECT * FROM tbl_plans WHERE coupon_type_id = '$current_plan'");
                $current_plan_row = mysqli_fetch_assoc($plan_query);

                $plan_name_mod = str_replace('_', ' ', substr($current_plan_row['coupon_type_id'], 1));
                $plan_val_months = explode(' ', $current_plan_row['validity'])[0];

                $new_exp = date("Y-m-d", strtotime("+" . $plan_val_months . " month", $current_expiry));
                $amount = floor($current_plan_row['price_inc_of_tax']);
                $round_off = $current_plan_row['price_inc_of_tax'] - $amount;
                // $current_plan_data =
            }
        }
    } else {

        $aerror = "Please Enter Valid Account ID ";
    }
}

?>


    <!-- **Main** -->
    <div id="main">
        <!-- **Breadcrumb Section** -->
        <div class="breadcrumb-section">
            <div class="container">
                <h1>Quick Pay</h1>
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
                    <div class="col-md-6">
                        <h2 class="hr-border-title"><span>Quick Pay</span></h2>

                        <form method="post" action="quick_pay.php" class="" name="frmnewconn">
                            <div class="clear"></div>
                            <div class="col-md-6 first">
                                <input class="form-control" type="text" name="mobileno" id="search_box"
                                       placeholder="Enter Mobile No" value="<?= @$_POST['mobileno'] ?>"
                                       required="required">
                                <span class="text-danger"><?= @$error ?></span>
                            </div>
                            <div class="col-md-6">
                                <input type="submit" name="btnmobile" value="Submit" style="float: left;"
                                       class="dt-sc-button small">
                            </div>
                            <div class="clear"></div>
                        </form>

                    </div>
                    <div class="col-md-6">
                        <h2 class="hr-border-title"><span></span></h2>

                        <form method="post" action="quick_pay.php" class="" name="frmnewconn">
                            <div class="clear"></div>
                            <div class="col-md-6 first">
                                <input class="form-control" type="text" name="act_id" id="search_box"
                                       placeholder="Enter Account ID" value="<?= @$_POST['act_id'] ?>"
                                       required="required">
                                <span class="text-danger"><?= @$aerror ?></span>
                            </div>
                            <div class="col-md-6">
                                <input type="submit" name="btnaccountid" value="Submit" style="float: left;"
                                       class="dt-sc-button small">
                            </div>
                            <div class="clear"></div>
                        </form>

                    </div>
                    <!-- **Primary Ends** -->
                </div>
                <?php if (!empty($customer_data)) { ?>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-danger">
                                <div class="panel-heading">Customer Info</div>
                                <div class="panel-body">
                                    <p><strong><?= $customer_data['fname'] ?>,</strong> <?= $customer_data['address'] ?>
                                        <br>
                                        <strong>Phone No :</strong><?= $customer_data['phone'] ?><br>
                                        <strong>Email :</strong><?= $customer_data['email'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-danger">
                                <div class="panel-heading">Current Subscription</div>
                                <div class="panel-body">
                                    <p><strong>Plan : </strong><?= $plan_name_mod ?> <br>
                                        <strong>Start Date : </strong><?= $subscriptions['startdt'] ?><br>
                                        <strong>Expiry Date : </strong><?= $subscriptions['expirydt'] ?><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            Connection Details
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Current Plan</th>
                                    <th>Start Date</th>
                                    <th>Plan Amount</th>
                                    <th>Tax(CGST+SGST)</th>
                                    <th>Expiry Date</th>
                                    <th>Total Payable</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td id="plan_id"><?= $plan_name_mod ?></td>
                                    <td id="start_date"><?= $subscriptions['startdt'] ?></td>
                                    <td id="plan_amount"><?= $current_plan_row['base'] ?></td>
                                    <td id="tax"><?= $current_plan_row['CGST'] + $current_plan_row['SGST'] ?></td>
                                    <!--<td id="expiry_date" ><?= $new_exp ?></td>-->
                                    <td id="expiry_date"><?= $subscriptions['expirydt'] ?></td>
                                    <td id="amount"><?= $current_plan_row['price_inc_of_tax'] ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="active_chk" checked="checked" value="0"> Activate
                                            Immediately
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="active_chk" value="1"> Activate After Expiry Date
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="active_chk" value="2"> Schedule Recharge Date
                                        </label>
                                    </div>
                                    <div class="col-md-6 hidden" id="date_control_group">
                                        <div class="col-md-6">
                                            <label for="">Select Date*</label>
                                            <input type="date" name="shedule_date" id="shedule_date"
                                                   value="<?php echo date("Y-m-d"); ?>"
                                                   min="<?php echo date("Y-m-d"); ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Select Time*</label>
                                            <input type="time" name="shedule_time" value="12:00:00" id="shedule_time"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <form class="form" action="payment.php" method="post">
                        <input type="hidden" name="amount" value="<?= $amount ?>" id="frm_amt">
                        <input type="hidden" name="plan_id" value="<?= $current_plan_row['Sr_no'] ?>" id="frm_plan_id">
                        <input type="hidden" name="plan_api_id" value="<?= $current_plan_row['coupon_type_id'] ?>"
                               id="frm_plan_api_id">
                        <input type="hidden" name="act_id" value="<?= $customer_data['actid'] ?>">
                        <input type="hidden" name="domain_id" value="<?= $customer_data['domid'] ?>">
                        <input type="hidden" name="subs_no" value="<?= $subscriptions['subsno'] ?>">
                        <input type="hidden" name="old_plan_id" value="<?= $current_plan_row['coupon_type_id'] ?>">
                        <input type="hidden" name="old_expiry"
                               value="<?= explode(' ', $subscriptions['expirydt'])[0] ?>">
                        <input type="hidden" name="active_type" id="frm_active" value="0">
                        <input type="hidden" name="schedule_date" id="frm_schedule_date" value="<?= date('Y-m-d') ?>">
                        <input type="hidden" name="shedule_time" id="frm_schedule_time" value="12:00:00">

                        <input type="hidden" name="billing_cust_name"
                               value="<?= $customer_data['fname'] . ' ' . $customer_data['lname'] ?>">
                        <input type="hidden" name="billing_cust_address" value="<?= $customer_data['address'] ?>">
                        <input type="hidden" name="billing_cust_city" value="<?= $customer_data['cityname'] ?>">
                        <input type="hidden" name="billing_cust_zip" value="<?= $customer_data['pin'] ?>">
                        <input type="hidden" name="billing_cust_state" value="<?= $customer_data['statename'] ?>">
                        <input type="hidden" name="billing_cust_country" value="India">
                        <input type="hidden" name="billing_cust_email" value="<?= $customer_data['email'] ?>">
                        <input type="hidden" name="billing_cust_tel" value="<?= $customer_data['mobileno'] ?>">
                        <input type="hidden" name="billing_cust_notes" value="Renewal Subscription">
                        <a class="btn btn-success" data-toggle="modal" data-target="#plan_modal">Change Plan</a>
                        <button type="submit" name="btnsend" value="" class="dt-sc-button small">Pay&nbsp;₹&nbsp;<span
                                    id="btn_amt"><?= $amount ?></span></button>
                    </form>

                </div>
            </div>
            <div id="plan_modal" class="modal fade" role="dialog" style="top: 30%;">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Select Plan</h4>
                        </div>
                        <div class="modal-body">
                            <div class="list-group" style="max-height: 180px; overflow-y: scroll;">

                                <?php foreach ($plans as $key => $plan) {
                                    $plan_name_mod2 = str_replace('_', ' ', substr($plan['coupon_type_id'], 1)); ?>
                                    <a class="list-group-item " id="<?= 'plan_' . $plan['Sr_no'] ?>"
                                       onclick="changePlan(this.id)" style="cursor: pointer;">
                                        <span class="badge"><?= $plan['price_inc_of_tax'] ?></span>
                                        <h4 class="list-group-item-heading"><?= $plan_name_mod2 . ' - ' . $plan['validity'] ?></h4>

                                    </a>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary nxtlnk" data-dismiss="modal">Next</button>
                        </div>

                    </div>

                </div>
            </div>
            <script>
                function changePlan(id) {
                    jQuery(".nxtlnk").prop('disabled', true);
                    jQuery(".nxtlnk").html('Selecting...');

                    jQuery('.list-group-item').removeClass('active');
                    jQuery('#' + id).addClass('active');
                    var post_id = id.split('_')[1];
                    jQuery.ajax({
                        url: 'NetworkAPI.php',
                        type: 'post',
                        data: {"method": "getPayDetails", "mobile": <?=$mobile?>, "id": post_id},
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            jQuery("#plan_id").html(response.plan_name_mod);
                            jQuery("#expiry_date").html(response.expiry_date);
                            //jQuery("#plan_name").html(response.plan_name_mod);
                            jQuery("#plan_amount").html('₹ ' + response.base);
                            jQuery("#tax").html('₹ ' + response.tax);
                            jQuery("#round_off").html('₹ ' + response.round_off);
                            jQuery("#amount").html('₹ ' + response.amount);
                            jQuery("#btn_amt").html(response.amount);
                            jQuery("#frm_amt").val(response.amount);
                            jQuery("#frm_plan_id").val(response.id);
                            jQuery("#frm_plan_api_id").val(response.plan_id);

                            jQuery(".nxtlnk").prop('disabled', false);
                            jQuery(".nxtlnk").html('Next');
                        }
                    });
                }

                jQuery(document).ready(function () {
                    jQuery("input[name=active_chk]").on('change', function () {
                        jQuery("#frm_active").val(this.value);
                        if (this.value != 2) {
                            jQuery("#date_control_group").addClass('hidden');
                        }
                        else
                            jQuery("#date_control_group").removeClass('hidden');
                    });

                    jQuery("#shedule_date").on('change', function () {
                        jQuery("#frm_schedule_date").val(this.value);
                    });
                    jQuery("#shedule_time").on('change', function () {
                        jQuery("#frm_schedule_time").val(this.value);
                    });

                });

            </script>
            <?php } ?>

            <!-- **Container Ends** -->
        </section>
        <!-- **Content Main Ends** -->

        <div style="height:100px">
            <div class="container">
                <!--<div id="tweets_container"></div>-->
            </div>
        </div>

    </div>
    <!-- **Main Ends** -->

<?php include 'footer.php'; ?>