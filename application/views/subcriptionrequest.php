<!-- Content Wrapper. Contains page content -->
<?php
$con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Subscription
        </h1>
        <ol class="breadcrumb">
            <li class="active">Subscription</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Subscription Request</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                <tr>
                                    <th>SR. No</th>
                                    <th>Name</th>
                                    <th>Address line 1</th>
                                    <th>Address line 2</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Pin Code</th>
                                    <th>Plan</th>
                                    <th>Status</th>
                                    <th>Connection</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    foreach ($subrequest as $result) {
                                        $plan_query = mysqli_query($con, "SELECT * FROM tbl_plans WHERE Sr_no = '$result->plan'");
                                        $current_plan_row = mysqli_fetch_assoc($plan_query);
                                        ?>
                                    <tr>
                                        <td><?php echo $result->Sr_no; ?></td>
                                        <td><?php echo $result->f_name . ' ' . $result->l_name; ?></td>
                                        <td><?php echo $result->addr1; ?></td>
                                        <td><?php echo $result->addr2; ?></td>
                                        <td><?php echo $result->city; ?></td>
                                        <td><?php echo $result->state; ?></td>
                                        <td><?php echo $result->pincode; ?></td>
                                        <td><?php echo str_replace('_', ' ', substr($current_plan_row['coupon_type_id'], 1)); ?></td>
                                        <td><?php echo $result->conn_home_offce; ?></td>
                                        <td><?php echo $result->status; ?></td>
                                        <td><?php echo $result->mobile; ?></td>
                                        <td><?php echo $result->email; ?></td>
                                        <td><a href="https://www.srilakshminetworks.com/newsite/new_subscription.php?mobile_no_search=<?php echo$result->mobile?>">
                                                <i class="fa fa-circle"></i>
                                                <span>View</span>
                                            </a></td>
                                    </tr>
                                        <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
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
    <!-- /.content -->
</div>
