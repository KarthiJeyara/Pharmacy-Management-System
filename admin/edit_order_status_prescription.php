<?php
// require_once('../database/db.php'); // Include database connection

require_once('../Classes/DbConnector.php');
require_once('../Classes/Administrator.php');

// Check if user is not logged in, redirect to login page
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

$user_type = 'admin'; // Define user type
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin | Edit Order Status</title>
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

    <!-- DataTables Css -->
    <link href="../common/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../common/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../common/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../common/plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Select Css -->
    <link href="../common/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Sweetalert2 -->
    <link href="../common/plugins/sweetalert2/style.css" type="text/css" rel="stylesheet">
    <link href="../common/plugins/sweetalert2/sweetalert.css" type="text/css" rel="stylesheet">
    <script src="../common/plugins/sweetalert2/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="../common/plugins/sweetalert2/sweetalert.min.js" type="text/javascript"></script>

    <!-- Custom Css -->
    <link href="../common/css/style.css" rel="stylesheet">
</head>

<body class="theme-cyan">

    <?php include '../common/includes/top.php' ?>

    <section>
        <?php include '../common/includes/left_side_bar.php' ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <ol class="breadcrumb breadcrumb-bg-teal align-right" style="background-color:#333 !important;">
                <li><a href="javascript:void(0);">Orders</a></li>
            </ol>

            <!-- Begin - Edit order form -->
            <div class="">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="pull-left no-margin">Edit Order Status</h3>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <form method="post" enctype="multipart/form-data">
                                        <?php
                                        include_once '../database/db.php';
                                        // Fetch order details based on edit_id
                                        if (isset($_GET['edit_id'])) {
                                            list($id, $code, $total_amount, $status, $order_deliver_address) = $admin->editOrderStatusGetExistingData($connection, $_GET['edit_id']);
                                        }
                                        ?>
                                        <div class="body">
                                            <label>Code</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" disabled
                                                        value="<?php echo $code; ?>">
                                                </div>
                                            </div>
                                            <label>Total Amount</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="price"
                                                        value="<?php echo $total_amount; ?>">
                                                </div>
                                            </div>
                                            <label>Status</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="status">
                                                        <option selected value="<?php echo $status; ?>">
                                                            <?php echo ucfirst($status); ?>
                                                        </option>
                                                        <option disabled> -- Choose Status -- </option>
                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="packed">Packed</option>
                                                        <option value="out for delivery">Out for Delivery</option>
                                                        <option value="done">Done</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3">
                                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                                                        name="update">Update</button>
                                                    <a href="order_list_prescription.php"><button type="button"
                                                            class="btn btn-danger m-t-15 waves-effect">Cancel</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End - Edit order form -->

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

    <!-- Custom Js -->
    <script src="../common/js/admin.js"></script>

    <!-- Sweetalert2 -->
    <script src="../common/plugins/sweetalert2/sweetalert.min.js"></script>
    <script>
        // SweetAlert success or error message
        $(document).ready(function () {
            <?php if (isset($successStatus) && $successStatus == 1): ?>
                swal({
                    title: "SUCCESS!",
                    text: "",
                    type: "success"
                }, function () {
                    window.location = "order_list.php";
                });
            <?php elseif (isset($successStatus) && $successStatus == 0): ?>
                swal({
                    title: "ERROR",
                    text: "",
                    type: "error"
                }, function () {
                    window.location = "order_list.php";
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>

<!-- Update data to database -->
<?php
if (isset($_POST['update'])) {
    $pr_id = $_GET['edit_id'];
    $status = $_POST['status'];
    $price = $_POST['price'];

    $admin->editOrderStatusPerscription($connection, $pr_id, $price, $status);
}
?>
<!-- End Update data to database -->