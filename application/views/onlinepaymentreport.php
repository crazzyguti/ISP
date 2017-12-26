<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Online Payment
        </h1>
        <ol class="breadcrumb">
            <li class="active">Online Payment</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Online Payment Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form class="form-inline">
                            <div class="form-group">
                                <select type="text" name="paid" placeholder="Select Employee"
                                        class="form-control">
                                    <option value="" class="">Select Option</option>
                                    <option value="0">Mobile Number</option>
                                    <option value="1">User ID</option>
                                    <option value="2">Email ID</option>
                                    <option value="3">External ID</option>
                                    <option value="4">Transaction ID</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Type Here">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                <tr>
                                    <th>External ID</th>
                                    <th>Customer Name</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Transaction Id</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Response message</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
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
