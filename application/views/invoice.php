<?php
//$con = mysqli_connect("localhost", "sri_billing_user", "sri_billing_pass", "sri_billing_db");
$con = mysqli_connect("localhost", "srilakshmi_db", "Erick@2199", "staging_db_srilakshmi");
//$con = mysqli_connect("localhost", "root", "", "cias");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Invoice
        </h1>
        <ol class="breadcrumb">
            <li class="active">Invoice</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Invoice Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="invoicetable">
                                    <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Account ID</th>
                                        <th>Mobile</th>
                                        <th>Plan</th>
                                        <th>Invoice Amount</th>
                                        <th>Invoice Date</th>
                                        <th>Payment Mode</th>
                                        <th>Invoice Path</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $plan_query = mysqli_query($con, "SELECT t.id as 'tid',t.*,p.* FROM tbl_transaction t JOIN tbl_plans p on t.plan_id=p.Sr_no WHERE t.status='success' and (t.type='Renewal Subscription' or t.type='New Subscription') ");
                                    while ($row = mysqli_fetch_array($plan_query)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['tid'] ?></td>
                                            <td><?php echo $row['actid'] ?></td>
                                            <td><?php echo $row['mobile'] ?></td>
                                            <td><?php echo str_replace('_', ' ', $row['rate_plan']); ?></td>
                                            <td><?php echo $row['amount'] ?></td>
                                            <td><?php echo $row['transaction_date'] ?></td>
                                            <td><?php echo $row['payment_mode'] ?></td>
                                            <td><?php echo $row['invoicelink'] ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                                            data-toggle="dropdown">Action
                                                        <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a target="_blank"
                                                               href="https://www.srilakshminetworks.com/staging/invoice_generate.php?invoice_id=<?php echo base64_encode($row['tid']); ?>"
                                                            >Generate Invoice</a>
                                                        </li>
                                                        <li>
                                                            <a target="_blank"
                                                               onclick="showReplyBox(<?php echo "'" . $row['tid'] . "'"; ?>);"
                                                            >Send Invoice</a>
                                                        </li>
                                                        <li>
                                                            <a target="_blank"
                                                               href="https://www.srilakshminetworks.com/staging/invoice_print.php?invoice_id=<?php echo base64_encode($row['tid']); ?>"
                                                            >Print Invoice</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
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
        <div class="modal fade" id="model_data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Send Email</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="staginsubmit" id="staginsubmit">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-7">
                                    <input value="" class="form-control" name="id_trans" id="id_trans" type="hidden">
                                    <input type="text" class="form-control" name="email" id="email"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label"></label>
                                <div class="col-sm-7">
                                    <input type="button" class="btn btn-primary" value="Send Email" name="savesend"
                                           id="savesend">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.row -->
    </section>
    <script>
        function showReplyBox(customerid) {
            console.log(customerid);
            $.ajax({
                type: 'post',
                url: 'invoice/invoicedetails',
                data: 'id=' + customerid,
                success: function (data) {
                    console.log(data);
                    $('#id_trans').val(customerid);
                    $('#email').val(data);
                    $('#model_data').modal('show');
                },
                error: function (data) {
                    if (data.statusText == 'Internal Server Error') {
                        dynamic_modal("Message", data.statusText);
                    } else {
                        var data_res = '';
                        for (var key in data.responseJSON) {
                            data_res += data.responseJSON[key] + '<br>';
                        }
                        dynamic_modal("Message", data_res);
                    }
                }
            });
        }

        $('#savesend').click(function () {

            var post_vars = $('#staginsubmit').serialize();
            var idtran = $('#id_trans').val();
            var email = $('#email').val();

            console.log(email);
            console.log(idtran);

            if (email == '') {
                alert("You Must Enter Email");
            } else {
                $.ajax({
                    type: 'post',
                    url: 'https://www.srilakshminetworks.com/staging/invoice_send.php',
                    data: post_vars,
                    success: function (data) {
                        console.log(data);
                        if (data = 'Invoice Send Successfully') {
                            $('#model_data').modal('hide');
                            alert(data);
                        }

                    },
                    error: function (data) {
                        if (data.statusText == 'Internal Server Error') {
                            dynamic_modal("Message", data.statusText);
                        } else {
                            var data_res = '';
                            for (var key in data.responseJSON) {
                                data_res += data.responseJSON[key] + '<br>';
                            }
                            dynamic_modal("Message", data_res);
                        }
                    }
                });
            }

        });
    </script>
    <!-- /.content -->
</div>
