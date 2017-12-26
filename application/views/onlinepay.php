<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer
        </h1>
        <ol class="breadcrumb">
            <li class="active">Online Pay</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><b>Pay Broadband Bill</b></h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="box box-solid">
                                    <div class="box-header with-border label-success">
                                        <h3 class="box-title">Customer Info</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <address style="font-size: 16px;margin-"><b>srinivascool.ann</b>, 003 kanthi
                                            kiran 7th cross phase 2null
                                            Bangalore-560100
                                        </address>
                                        Phone: 9884858059
                                        <div>
                                            E-Mail:
                                        </div>
                                        <div>
                                            <input type="text" value="text@gmail.com" class="form-control">
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- ./col -->
                            <!-- ./col -->
                            <div class="col-md-3 pull-right">
                                <div class="box box-solid">
                                    <div class="box-header with-border label-success">
                                        <h3 class="box-title">Operator Info</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <address><b>Sri Lakshmi Networks,</b>
                                            60-2,Ananthanagar layout, first phase, first left cross, krishnappa
                                            building, behind canara bank atm, Bengaluru 560100.
                                        </address>
                                        Phone: 08049587000
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- ./col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border label-success">
                                        <h3 class="box-title">Connection Details</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table class="table table-responsive">
                                            <tr>
                                                <th>#Select Plan</th>
                                                <th>Current Plan</th>
                                                <th>Expiry Date</th>
                                                <th>Plan Name</th>
                                                <th>Plan Amount</th>
                                                <th>Tax</th>
                                                <th>Round Off</th>
                                                <th>Total Payable</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div ng-hide="cus_details.customerBill.client_id=='MYB00047' || cus_details.customerBill.client_id=='MYB00040'"
                                                         class="control-group">
                                                        <div class="input-group" style="width: 90%;">
                                                            <input ng-disabled="showpipe" type="search"
                                                                   placeholder="Search Plan"
                                                                   class="form-control ng-pristine ng-valid"
                                                                   ng-model="keyword" ng-change="hide()">
                                                            <div ng-hide="showpipe" class="dropdown-heigth">
                                                                <ul class="list-group">
                                                                    <!-- ngRepeat: items in plans | filter:keyword -->
                                                                    <li class="list-group-item ng-scope"
                                                                        ng-repeat="items in plans | filter:keyword"
                                                                        ng-click="ppselected(items)">
                                                                        <span class="badge ng-binding">₹ 521.19</span>
                                                                        <p class="ng-binding"> a_50_Mbps_30_GB (30
                                                                            Days)</p>
                                                                        <p class="ng-binding">a_50_Mbps_30_GB</p>
                                                                        <p class="ng-binding">Post FUP-50 Mbps 30 Gb</p>
                                                                    </li>
                                                                    <!-- end ngRepeat: items in plans | filter:keyword -->
                                                                    <li class="list-group-item ng-scope"
                                                                        ng-repeat="items in plans | filter:keyword"
                                                                        ng-click="ppselected(items)">
                                                                        <span class="badge ng-binding">₹ 694.92</span>
                                                                        <p class="ng-binding"> a_50_Mbps_100_GB (30
                                                                            Days)</p>
                                                                        <p class="ng-binding">a_50_Mbps_100_GB</p>
                                                                        <p class="ng-binding">Post FUP-50 Mbps 100
                                                                            Gb</p>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>a_50_Mbps_150_GB</td>
                                                <td>2018-01-08</td>
                                                <td>a_50_Mbps_150_GB</td>
                                                <td><i class="fa fa-inr"></i> 869</td>
                                                <td><i class="fa fa-inr"></i>156.36</td>
                                                <td><i class="fa fa-inr"></i> -0.36</td>
                                                <td><i class="fa fa-inr"></i>1025</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="box box-solid">
                                   <input type="button" class="btn btn-primary" value="BACK">
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- ./col -->
                            <!-- ./col -->
                            <div class="col-md-1 pull-right">
                                <div class="box box-solid">
                                    <input type="button" class="btn btn-primary" value="PAY">
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- ./col -->
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
