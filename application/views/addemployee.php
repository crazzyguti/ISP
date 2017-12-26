    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Customer
            </h1>
            <ol class="breadcrumb">
                <li class="active">Add Profile</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- /.box -->

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Customer Details</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div  class="dataTable_wrapper">
                                <h4><b> Login Information</b></h4>
                                <hr>
                                <div class="row">
                                    <div class='col-sm-12 col-md-6'>
                                        <form role="form">
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                                <input id="employee_id" name="employee_id" type="text"
                                                       placeholder="[Auto]"
                                                       class="form-control" readonly="readonly">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                <input id="employee_name" name="employee_name" type="text"
                                                       placeholder="Enter the employee name"
                                                       class="form-control"
                                                       required="">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <input data-pk="1" type="email" name="employee_email"
                                                       placeholder="Enter the employee email ID"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                                <select type="text" data-pk="1" placeholder="Select the department"
                                                        class="form-control"
                                                >
                                                    <option value="" class="">Select Department</option>
                                                    <option value="0">Adminstration</option>
                                                    <option value="1">Operation</option>
                                                    <option value="2">Services</option>
                                                    <option value="3">Collections</option>
                                                    <option value="4">Receptionist</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="pull-right" style="margin-right: 50px;">
                                            <img style="height: 120px; width: 70px;" src="profile.png">
                                        </div>
                                    </div>
                                </div>
                                <h4><b> Location</b></h4>
                                <hr>
                                <div class="row">
                                    <div class='col-sm-12 col-md-6'>
                                        <form role="form">
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                                <input href="#" id="line1" type="text" data-pk="1"
                                                       placeholder="Enter the address line 1"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                                <input href="#" id="line2" type="text" data-pk="1"
                                                       placeholder="Enter the Address line 2"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-building-o"></i> </span>
                                                <input type="text" id="Autocomplete2"
                                                       class="form-control"
                                                       required="">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-building-o"></i> </span>
                                                <input type="text" id="Autocomplete2"
                                                       class="form-control"
                                                       required="">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-arrow-right"></i> </span>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="Enter the zip code">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-area-chart"></i></span>
                                                <select type="text" data-pk="1" placeholder="Select Area Code"
                                                        class="form-control">
                                                    <option value="" class="">Select Department</option>
                                                    <option value="0">Check All</option>
                                                    <option value="1">Uncheck All</option>
                                                    <option value="2">Services</option>
                                                    <option value="3">Collections</option>
                                                    <option value="4">Receptionist</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <h4><b> Other Information</b></h4>
                                <hr>
                                <div class="row">
                                    <div class='col-sm-12 col-md-6'>
                                        <form role="form">
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input id="joining_date" type="date" placeholder="Select Joining Date"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group input-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadioG" id="inlineRadioA"
                                                           class=""
                                                           checked="checked"> Male
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadioG" id="inlineRadioB"
                                                           class=""
                                                           checked="checked"> Female
                                                </label>
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-mobile"></i> </span>
                                                <input name="contact_number" type="text"
                                                       placeholder="Enter the customer mobile number"
                                                       class="form-control"
                                                       maxlength="10">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-phone"></i> </span>
                                                <input id="joining_date" type="text" data-pk="1"
                                                       placeholder="Enter the employee landline number"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-signal"></i> </span>
                                                <select id="assign_device" type="text"
                                                        class="form-control"
                                                        style="width:220px;">
                                                    <option value="0">Select device</option>
                                                </select>
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                                                <input id="password" type="password"
                                                       placeholder="Enter the Password"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                                                <input id="cpassword" type="password" placeholder="Confirm Password"
                                                       class="form-control">
                                            </div>
                                            <br>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="reset" class="btn btn-default">Reset</button>
                                        </form>
                                    </div>
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
