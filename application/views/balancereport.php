<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Balance
        </h1>
        <ol class="breadcrumb">
            <li class="active">Balance</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Balance Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form class="form-inline">
                            <div class="form-group">
                                <label>Employee</label>
                                <select type="text" name="paid" placeholder="Select Employee"
                                        class="form-control">
                                    <option value="0">Admin</option>
                                    <option value="1">All</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                <tr>
                                    <th>Account Name</th>
                                    <th>Employee Name</th>
                                    <th>External ID</th>
                                    <th>User ID</th>
                                    <th>Contact Number</th>
                                    <th>Created On</th>
                                    <th>Address</th>
                                    <th>Area Code</th>
                                    <th>Paid Amount</th>
                                    <th>Balance</th>
                                    <th>Payable</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
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
