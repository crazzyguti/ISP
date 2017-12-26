<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Complaint
        </h1>
        <ol class="breadcrumb">
            <li class="active">Complaint</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Complaint Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Complaint ID</th>
                                    <th>User Id</th>
                                    <th>External Id</th>
                                    <th>Account Name</th>
                                    <th>Area</th>
                                    <th>Employee Name</th>
                                    <th>Complaint Details</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Created On</th>
                                    <th>Closed Date</th>
                                    <th>Source</th>
                                    <th>Duration</th>
                                    <th>Duration</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>MYB02007C17-18/6</td>
                                    <td>aunshak.ann</td>
                                    <td></td>
                                    <td>aunshak</td>
                                    <td></td>
                                    <td></td>
                                    <td>Payment Collection Request</td>
                                    <td>Open</td>
                                    <td></td>
                                    <td>03/11/2017 18:52:10</td>
                                    <td></td>
                                    <td>Customer</td>
                                    <td></td>
                                    <td></td>
                                    <td class="hidden-phone ">
                                        <div class="btn-group">
                                            <button data-toggle="dropdown" class="btn btn-mini dropdown-toggle">
                                                Action
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li ng-if="item.status=='Open' " class="ng-scope"><a
                                                            class="btn btn-warning2 btn-mini"
                                                            ng-click="show(item.complaint_id)">
                                                        Assign
                                                    </a></li>
                                                <li><a class="btn btn-warning2 btn-mini" ng-click="showIp(item.customerId)">
                                                        View IP Addresses
                                                    </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
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
