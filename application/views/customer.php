<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer
        </h1>
        <ol class="breadcrumb">
            <li class="active">Customer</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><b>Customer Details</b></h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">
                        <?php
                        if (isset($error)) {
                            ?>
                            <div class="alert alert-danger alert-dismissible col-md-6 col-sm-12">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <?php
                                echo "<i class=\"icon fa fa-ban\"></i>" . $error;
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        if (isset($success)) {
                            ?>
                            <div class="alert alert-success alert-dismissible col-md-6 col-sm-12">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <?php
                                echo "<i class=\"icon fa fa-ban\"></i>" . $success;
                                if (isset($order_id)) {
                                    header('Location:https://www.srilakshminetworks.com/invoice.php?invoice_id=' . base64_encode($order_id));
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div style="clear: both;">
                        </div>
                        <hr>
                        <form class="form-inline" method="post" action="<?php echo base_url(); ?>customer/search">
                            <div class="form-group">
                                <label>User ID / Account Name</label>
                                <input class="form-control" name="user_id" type="text"
                                       placeholder="User ID / Account Name">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input class="form-control" name="contactnumber" type="text"
                                       placeholder="Contact Number">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>


                        <hr>
                        <br>
                        <div class="dataTable_wrapper">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="example1">
                                    <thead>
                                    <tr>
                                        <th>Account Id</th>
                                        <th>Account Name</th>
                                        <th>Address</th>
                                        <th>Mobile</th>
                                        <th>Balance</th>
                                        <th>Bill Amount</th>
                                        <th>Carried Balance</th>
                                        <th>Email</th>
                                        <th>Invoice Date</th>
                                        <th>Paid Status</th>
                                        <th>Plan Amt(Incl.Tax)</th>
                                        <th>Plan</th>
                                        <th>Plan ExpireDate</th>
                                        <th>PlanId</th>
                                        <th>Pan Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($result)) {
                                        if ($result['actcat'] == 1) {
                                            $actcat = 'Advanced-Pay';
                                        } else if ($result['actcat'] == 1) {
                                            $actcat = 'Post-pay';
                                        } else if ($result['actcat'] == 1) {
                                            $actcat = 'Pre-pay';
                                        }
                                        $message = '';
                                        if ($subscriptions['status'] == 0) {
                                            $status = 'Active';
                                            $message = "<div class=\"btn btn-success btn-xs\">" . $status . "</div>";
                                        } else if ($subscriptions['status'] == 15) {
                                            $status = 'Expired';
                                            $message = "<div class=\"btn btn-danger btn-xs\">" . $status . "</div>";
                                        }
                                        ?>
                                        <td><?php echo $result['actid']; ?></td>
                                        <td><?php echo $result['actname']; ?></td>
                                        <td><?php echo $result['address']; ?></td>
                                        <td><?php echo $result['mobileno']; ?></td>
                                        <td><?php if (isset($result['balance'])) echo $result['balance']; else  echo $result['accbalance']; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $result['email']; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $subscriptions['ratePlan']; ?></td>
                                        <td><?php echo $subscriptions['expirydt']; ?></td>
                                        <td></td>
                                        <td><?php echo $actcat; ?></td>
                                        <td><?php echo $message; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                        data-toggle="dropdown">Action
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#"
                                                           onclick="showReplyBox(<?php echo "'" . $result['actid'] . "'"; ?>,<?php echo "'" . $subscriptions['ratePlan'] . "'"; ?>);">Renewal</a>
                                                    </li>
                                                    <!--<li><a href="customer/onlinepay">Online
                                                            Pay</a></li>
                                                    <li><a href="#" data-toggle="modal"
                                                           data-target="#modal-sync">Sync</a></li>-->
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>customer/editCustomer">Edit</a>
                                                    </li>
                                                    <li><a href="#" data-toggle="modal" data-target="#modal-ledger">General
                                                            Ledger</a></li>
                                                    <li><a href="#" data-toggle="modal"
                                                           data-target="#modal-subscription">Subscription</a></li>
                                                    <li><a href="#" data-toggle="modal"
                                                           data-target="#modal-block">Block</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#modal-disable">Disable</a>
                                                    </li>
                                                    <li><a href="#" data-toggle="modal"
                                                           data-target="#modal-AddComplaint">Add Complaint</a></li>
                                                    <!--<li><a href="#" data-toggle="modal" data-target="#modal-netid">NET
                                                            ID</a></li>-->
                                                </ul>
                                            </div>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Renewal</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" action="<?php echo base_url(); ?>customer/paymentData">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Customer Name
                                </label>
                                <div class="col-sm-7">
                                    <input type="email" readonly class="form-control" id="customeridname"
                                           name="customeridname"
                                           placeholder="Customer Name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Current Plan</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="currentplan" readonly name="currentplan"
                                           placeholder="Current Plan">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Payment Mode
                                </label>
                                <div class="col-sm-7">
                                    <select name="paymentmode" id="paymentmode" class="form-control">
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Cheque Number/Date
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="chqunumberdate" name="chqunumberdate"
                                           placeholder="Cheque Number/Date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">
                                </label>
                                <div class="col-sm-7">
                                    <input type="radio" value="immediate" checked name="activeplan"
                                           id="activeplan"><label>Activate
                                        Immediately</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">
                                </label>
                                <div class="col-sm-7">
                                    <input type="radio" value="afterexpire" name="activeplan"
                                           id="activeplan"><label>Activate After
                                        Expiry Date</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">
                                </label>
                                <div class="col-sm-7">
                                    <input type="radio" value="schedulerecharge" name="activeplan"
                                           id="activeplan"><label>Schedule Recharge Date</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id='changeplan'> Check to Change the Plan
                                        </label>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <?php

                        if (!isset($error) && isset($amount)) {
                            ?>
                            <div class="form-group" id="showhindplan" style="display: none;">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="list-group" style="max-height: 163px; overflow-y: scroll;">
                                        <?php foreach ($plans as $key => $plan) { ?>
                                            <a class="list-group-item " id="<?= 'plan_' . $plan->Sr_no ?>"
                                               onclick="changePlan(<?= $plan->Sr_no ?>)" style="cursor: pointer;">
                                                <span class="badge"> <?= $plan->price_inc_of_tax ?> </span>
                                                <h4 class="list-group-item-heading"><?= str_replace('_', ' ', $plan->rate_plan) . ' - ' . $plan->validity ?></h4>
                                                <p class="list-group-item-text"> <?= str_replace('_', ' ', $plan->rate_plan) ?> </p>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="amount" value="<?= $amount ?>" id="frm_amt">
                            <input type="hidden" name="plan_id" value="<?= $current_plan_row[0]->Sr_no ?>"
                                   id="frm_plan_id">
                            <input type="hidden" name="plan_api_id" value="<?= $current_plan_row[0]->coupon_type_id ?>"
                                   id="plan_api_id">
                            <input type="hidden" name="act_id" value="<?= $result['actid'] ?>">
                            <input type="hidden" name="expiry_date" value="<?= $subscriptions['expirydt'] ?>">
                            <input type="hidden" name="domain_id" value="<?= $result['domid'] ?>">
                            <input type="hidden" name="subs_no" value="<?= $subscriptions['subsno'] ?>">
                            <input type="hidden" name="old_plan_id" value="<?= $current_plan_row[0]->coupon_type_id ?>">
                            <input type="hidden" name="billing_cust_name"
                                   value="<?= $result['fname'] . ' ' . $result['lname'] ?>">
                            <input type="hidden" name="billing_cust_address" value="<?= $result['address'] ?>">
                            <input type="hidden" name="billing_cust_city" value="<?= $result['cityname'] ?>">
                            <input type="hidden" name="billing_cust_zip" value="<?= $result['pin'] ?>">
                            <input type="hidden" name="billing_cust_state" value="<?= $result['statename'] ?>">
                            <input type="hidden" name="billing_cust_country" value="India">
                            <input type="hidden" name="billing_cust_email" value="<?= $result['email'] ?>">
                            <input type="hidden" name="billing_cust_tel" value="<?= $result['mobileno'] ?>">
                            <input type="hidden" name="billing_cust_notes" value="Renewal Subscription">
                            <script>
                                function changePlan(id) {
                                    $('#submitdata').prop('disabled', true);
                                    jQuery('.list-group-item').removeClass('active');
                                    jQuery('#plan_' + id).addClass('active');
                                    var post_id = id;
                                    jQuery.ajax({
                                        url: '<?php echo base_url(); ?>customer/getPayDetails',
                                        type: 'post',
                                        data: {
                                            "method": "getPayDetails",
                                            "mobile": <?=$result['mobileno']?>,
                                            "id": post_id
                                        },
                                        dataType: "json",
                                        success: function (response) {
                                            console.log(response, 'response');
                                            jQuery("#plan_id").val(response.id);
                                            jQuery("#plan_api_id").val(response.plan_id + "");
                                            // jQuery("#expiry_date").val(response.expiry_date + "");
                                            //jQuery("#plan_name").val(response.plan_name);
                                            //  jQuery("#plan_amount").val('₹ ' + response.plan_amount);
                                            //  jQuery("#tax").val('₹ ' + response.tax + "");
                                            // jQuery("#round_off").val('₹ ' + response.round_off + "");
                                            jQuery("#amount").val('₹ ' + response.amount + "");
                                            //  jQuery("#btn_amt").val(response.amount + "");
                                            jQuery("#frm_amt").val(response.amount + "");
                                            jQuery("#frm_plan_id").val(response.id + "");
                                            $('#submitdata').prop('disabled', false);
                                        },
                                        error: function (xhr, status, error) {
                                            var err = eval("(" + xhr.responseText + ")");
                                            alert(err.Message);
                                        }
                                    });
                                }

                                jQuery('#changeplan').click(function () {
                                    if (jQuery(this).prop("checked") == true) {
                                        jQuery("#showhindplan").show();
                                    }
                                    else if (jQuery(this).prop("checked") == false) {
                                        jQuery("#showhindplan").hide();
                                    }
                                });

                            </script>
                            <?php
                        }
                        ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="submitdata">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-sync">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmation Message</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure that you want to Sync ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Ok</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-ledger">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmation Message</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="example3">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Entry Type</th>
                                <th>Entry Description</th>
                                <th>Entry Date</th>
                                <th>Final Balance</th>
                                <th>Entry Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>DEBIT</td>
                                <td>PAYMENT Cash RECEIPT-17-18/392</td>
                                <td>08/11/2017 19:47:49</td>
                                <td> 0</td>
                                <td>-1025</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>CREDIT</td>
                                <td>Invoice B007171800000388</td>
                                <td>08/11/2017 19:47:49</td>
                                <td>1025</td>
                                <td>1025</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-subscription">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Subscription</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Unify PlanId
                                </label>
                                <div class="col-sm-7">
                                    <input type="email" disabled class="form-control" value="a_50_Mbps_150_GB"
                                           id="inputEmail3" placeholder="Customer Name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Start Date
                                </label>

                                <div class="col-sm-7">
                                    <input type="password" class="form-control" disabled value="08-11-2017"
                                           id="inputPassword3"
                                           placeholder="Current Plan">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Expiry Date
                                </label>

                                <div class="col-sm-7">
                                    <input type="email" class="form-control" disabled id="inputEmail3"
                                           placeholder="Payment Mode">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-block">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmation Message</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure that you want to Block Customer?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Ok</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-disable">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmation Message</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure that you want to Disable Customer?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Ok</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-AddComplaint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Renewal</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">User ID
                                </label>
                                <div class="col-sm-7">
                                    <input type="email" class="form-control" value="srinivascool.ann" disabled
                                           id="inputEmail3"
                                           placeholder="Customer Name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Complaint type
                                </label>
                                <div class="col-sm-7">
                                    <select type="text" id="complaint_type" class="form-control">
                                        <option value="" class="">Select Complaint Type</option>
                                        <option value="0">Payment Related Issue</option>
                                        <option value="1">Frequent Disconnection</option>
                                        <option value="2">Internet is not working</option>
                                        <option value="3">Slow Speed Internet</option>
                                        <option value="4">Router Configuration</option>
                                        <option value="5">Reconnection Request</option>
                                        <option value="6">Shifting Request</option>
                                        <option value="7">Temporary Disconnection Request</option>
                                        <option value="8">Disconnection Request</option>
                                        <option value="9">Errors in Login Page</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-netid">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Net Id</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="example3">
                            <thead>
                            <tr>
                                <th>Device ID</th>
                                <th>NET ID Key</th>
                                <th>NET ID Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>PC1</td>
                                <td>UPWD</td>
                                <td>srinivascool.ann</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.content -->

</div>
<script>
    function showReplyBox(customerid, planname) {
        console.log(customerid);
        $("#customeridname").val(customerid);
        $("#currentplan").val(planname);
        $('#modal-default').modal('toggle');
    }
</script>
