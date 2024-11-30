<?php
// require_once('../database/db.php');
require_once('../Classes/Administrator.php');
require_once('../Classes/DbConnector.php');

if (!isset($_SESSION["username"])) {
    header('location:../login/index.php');
    exit();
} else {
    $username = $_SESSION["username"];
    $type = $_SESSION["type"];
    if ($type != 'admin') {
        header('location:../login/index.php');
        exit();
    }
}

$connection = (new DbConnector())->getConnection();
$admin = new Administrator($username, null);

$user_type = 'admin';

if (isset($_GET['view_id'])) {
    $order_details = $admin->viewOrderDetails($connection, $_GET['view_id']);
} else {
    header('location:order_list.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin | Orders</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="../common/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="../common/plugins/node-waves/waves.css" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="../common/plugins/animate-css/animate.css" rel="stylesheet" />
    <!-- JQuery DataTable Css -->
    <link href="../common/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="../common/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
        rel="stylesheet" />
    <!-- Bootstrap DatePicker Css -->
    <link href="../common/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
    <!-- Wait Me Css -->
    <link href="../common/plugins/waitme/waitMe.css" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="../common/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="../common/css/style.css" rel="stylesheet">
    <!-- Switchery css -->
    <link href="../common/plugins/switchery/switchery.min.css" rel="stylesheet" />
    <!-- DataTables -->
    <link href="../common/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../common/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../common/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../common/plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../common/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Sweet alert -->
    <link href="../common/plugins/sweetalert2/style.css" type="text/css" rel="stylesheet">
    <link href="../common/plugins/sweetalert2/sweetalert.css" type="text/css" rel="stylesheet">
    <script src="../common/plugins/sweetalert2/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="../common/plugins/sweetalert2/sweetalert.min.js" type="text/javascript"></script>
    <!-- -->
    <link href="../common/css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-cyan">

    <?php include '../common/includes/top.php'; ?>

    <section>
        <?php include '../common/includes/left_side_bar.php'; ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <ol class="breadcrumb breadcrumb-bg-teal align-right" style="background-color:#333 !important;">
                <li><a href="javascript:void(0);">Admin | Order Management</a></li>
            </ol>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Customer's Order Details</h3>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card" style="padding:10px">
                        <div class="card-body">
                            <h5 class="card-title"><b>Code : </b><?php echo ($order_details['o_code']); ?></h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Delivery Address :</b>
                                <?php echo htmlspecialchars($order_details['o_address']); ?></li>
                            <li class="list-group-item"><b>Grand Total:
                                </b><?php echo htmlspecialchars($order_details['o_total_amount']); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card" style="padding:10px">
                        <div class="card-body">
                            <h5 class="card-title">Prescription:</h5>
                            <ul class="list-group list-group-flush">
                                <?php if (($order_details['p_image']) != '') { ?>
                                    <img style="width: 400px;height:auto" id="img01"
                                        src="../customer/uploads/<?php echo ($order_details['p_image']); ?>" alt="" />

                                    <script>
                                        function img001(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();
                                                reader.onload = function (e) {
                                                    $('#img01')
                                                        .attr('src', e.target.result)
                                                };
                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                    </script>
                                <?php } else {
                                    echo "no image"; ?>
                                    <img style="width: 100px;height:auto" id="img01"
                                        src="../customer/uploads/<?php echo ($order_details['p_image']); ?>" alt="" />

                                    <script>
                                        function img001(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();
                                                reader.onload = function (e) {
                                                    $('#img01')
                                                        .attr('src', e.target.result)
                                                };
                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                    </script>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="../common/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="../common/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Select Plugin Js -->
    <script src="../common/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="../common/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="../common/plugins/node-waves/waves.js"></script>
    <!-- Autosize Plugin Js -->
    <script src="../common/plugins/autosize/autosize.js"></script>
    <!-- Moment Plugin Js -->
    <script src="../common/plugins/momentjs/moment.js"></script>
    <!-- Bootstrap Notify Plugin Js -->
    <script src="../common/plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="../common/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="../common/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <!-- Jquery DataTable Plugin Js -->
    <script src="../common/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="../common/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="../common/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="../common/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="../common/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="../common/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="../common/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="../common/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="../common/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <!-- Custom Js -->
    <script src="../common/js/admin.js"></script>
    <script src="../common/js/pages/tables/jquery-datatable.js"></script>
    <script src="../common/js/pages/ui/modals.js"></script>
    <script src="../common/js/pages/forms/basic-form-elements.js"></script>
    <!-- Demo Js -->
    <script src="../common/js/demo.js"></script>
</body>

</html>