<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Paid
        </h1>
        <ol class="breadcrumb">
            <li class="active">Paid</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Paid Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div  class="dataTable_wrapper">
                            <form class="form-inline">
                                <div class="form-group">
                                    <select id="assign_device"
                                            class="form-control">
                                        <option value="" class="">All</option>
                                        <option value="0">Admin</option>
                                        <option value="1">Online</option>
                                        <option value="2">CAF</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="date" name="fromdate" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="date" name="todate" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="example1">
                                    <thead>
                                    <tr>
                                        <th>Account Name</th>
                                        <th>External ID</th>
                                        <th>User ID</th>
                                        <th>Contact Number</th>
                                        <th>Created On</th>
                                        <th>Address</th>
                                        <th>Area Code</th>
                                        <th>Employee Name</th>
                                        <th>Paid Amount</th>
                                        <th>Invoice Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Date</th>
                                        <th>Transaction Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
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
    <!-- /.content -->
</div>
