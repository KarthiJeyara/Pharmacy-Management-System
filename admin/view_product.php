<?php
if (!isset($_GET['view_id'])) {
    header("Location:product_list.php");
}

require_once '../Classes/DbConnector.php';
require_once '../Classes/Administrator.php';

if (!$_SESSION["username"] == true) {
    header('location:../login/index.php');
} else {
    $username = $_SESSION["username"];
    $type = $_SESSION["type"];
    if ($type != 'admin') {
        header('location:../login/index.php');
    }
}

$admin = new Administrator($username, null);
$connection = (new DbConnector())->getConnection();

$user_type = 'admin';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin | Category</title>
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
    <!-- Responsive datatable examples -->
    <link href="../common/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Multi Item Selection examples -->
    <link href="../common/plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">

    <!-- Sweet alert -->
    <link href="../common/plugins/sweetalert2/style.css" type="text/css" rel="stylesheet">
    <link href="../common/plugins/sweetalert2/sweetalert.css" type="text/css" rel="stylesheet">
    <script src="../common/plugins/sweetalert2/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="../common/plugins/sweetalert2/sweetalert.min.js" type="text/javascript"></script>
    <!-- -->

    <link href="../common/css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-cyan">

    <?php include '../common/includes/top.php' ?>

    <section>
        <?php include '../common/includes/left_side_bar.php' ?>
    </section>

    <section class="content">
        <div class="container-fluid">

            <ol class="breadcrumb breadcrumb-bg-teal align-right" style="background-color:#333 !important;">
                <li><a href="javascript:void(0);">View Products</a></li>
            </ol>





            <!--Begin -->
            <div class="">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="pull-left no-margin">Product Details</h3>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <form method="post" enctype="multipart/form-data">
                                        <?php
                                        include_once '../database/db.php';
                                        if (isset($_GET['view_id'])) {
                                            list($id, $p_name, $p_description, $p_brand, $p_dose, $p_price, $p_status, $c_name, $img) = $admin->viewAProduct($connection, $_GET['view_id']);
                                        }
                                        ?>
                                        <div class="body">
                                            <label>Brand</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="text"><?php echo $p_brand; ?></div>
                                                </div>
                                            </div>
                                            <label>Name</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="text"><?php echo $p_name; ?></div>
                                                </div>
                                            </div>
                                            <label>Dose</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="text"><?php echo $p_dose; ?></div>
                                                </div>
                                            </div>
                                            <label>Category</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="text"><?php echo $c_name; ?></div>
                                                </div>
                                            </div>
                                            <label>Description</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="text"><?php echo $p_description; ?></div>
                                                </div>
                                            </div>
                                            <label>Price</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="text"><?php echo $p_price; ?></div>
                                                </div>
                                            </div>
                                            <label>Status</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div class="text"><?php echo $p_status; ?></div>
                                                </div>
                                            </div>
                                            <label>Image of product</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <?php if ($img != '') { ?>
                                                        <img style="width: 100px;height:auto" id="img01"
                                                            src="uploads/<?php echo $img; ?> " alt="" />

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
                                                            src="uploads/<?php echo $img; ?> " alt="" />

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
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3">
                                    <a href="product_list.php"><button type="button"
                                            class="btn btn-danger m-t-15 waves-effect">Back</button></a>
                                </div>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--##END-->



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

    <!-- Custom Js -->
    <script src="../common/js/admin.js"></script>
    <script src="../common/js/pages/tables/jquery-datatable.js"></script>
    <script src="../common/js/pages/ui/modals.js"></script>
    <script src="../common/js/pages/forms/basic-form-elements.js"></script>

    <!-- Demo Js -->
    <script src="../common/js/demo.js"></script>
</body>

</html>