<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pending
        </h1>
        <ol class="breadcrumb">
            <li class="active">UpComing</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!--  <div class="box">
                      <div class="box-header">

                      </div>
                      <div class="box-body"> -->
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
                        ?>
                    </div>
                    <?php
                }
                ?>
                <div style="clear: both;">
                </div>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        UpComing Recharge Details
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                <tr>
                                    <th>Contact No</th>
                                    <th>Account Id</th>
                                    <th>Expiry Date</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Recharge Due Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php

                                foreach($upcomingData as $row) {
                                    if($row['status'] == 'new'){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['mobile'] ?></td>
                                            <td><?php echo $row['actid'] ?></td>
                                            <td><?php echo $row['expiry_date'] ?></td>
                                            <td><?php echo str_replace('_', ' ', $row['rate_plan']); ?></td>
                                            <td><?php echo $row['amount'] ?></td>
                                            <td><?php echo $row['status'] ?></td>
                                            <td><?php echo $row['expire'] ?></td>
                                            <td>
                                                <a href="<?php echo base_url(); ?>upcoming/immediately?id=<?php echo $row['cid']; ?>"
                                                   class="btn btn-success">Immediate
                                                    Recharge</td>
                                        </tr>
                                        <?php
                                    }}
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="panel panel-success">
                    <div class="panel-heading">
                        Activated Recharge Details
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example3">
                                <thead>
                                <tr>
                                    <th>Contact No</th>
                                    <th>Account Id</th>
                                    <th>Expiry Date</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Recharge Due Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php

                                foreach($upcomingData as $row) {
                                    if($row['status'] != 'new'){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['mobile'] ?></td>
                                            <td><?php echo $row['actid'] ?></td>
                                            <td><?php echo $row['expiry_date'] ?></td>
                                            <td><?php echo str_replace('_', ' ', $row['rate_plan']); ?></td>
                                            <td><?php echo $row['amount'] ?></td>
                                            <td><?php echo $row['status'] ?></td>
                                            <td><?php echo $row['expire'] ?></td>
                                            <td>
                                                <a href="<?php echo base_url(); ?>upcoming/immediately?id=<?php echo $row['cid']; ?>"
                                                   class="btn btn-success">Immediate
                                                    Recharge</td>
                                        </tr>
                                        <?php
                                    }}
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>
            </div>
            <!-- /.box-body
        </div>
        <!-- /.box -->
        </div>
        <!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>