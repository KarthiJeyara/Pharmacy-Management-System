<?php
// Include database connection file
// require_once('../database/db.php'); 
require_once('../Classes/Administrator.php');
require_once('../Classes/DbConnector.php');

// Redirect to login page if user is not logged in
if (!isset($_SESSION["username"])) {
    header('Location: ../login/index.php');
    exit();
} else {
    $username = $_SESSION["username"];
    $type = $_SESSION["type"];
    if ($type != 'admin') {
        header('Location: ../login/index.php');
        exit();
    }
}

$connection = (new DbConnector)->getConnection();
$admin = new Administrator($username, null);

// Define user type as admin
$user_type = 'admin';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin | Edit Inventory</title>
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
    <link rel="stylesheet" href="../common/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Sweet alert -->
    <link href="../common/plugins/sweetalert2/style.css" type="text/css" rel="stylesheet">
    <link href="../common/plugins/sweetalert2/sweetalert.css" type="text/css" rel="stylesheet">
    <script src="../common/plugins/sweetalert2/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="../common/plugins/sweetalert2/sweetalert.min.js" type="text/javascript"></script>
    <!-- Themes -->
    <link href="../common/css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-cyan">
    <!-- Include top navigation bar -->
    <?php include '../common/includes/top.php' ?>

    <section>
        <!-- Include left side bar -->
        <?php include '../common/includes/left_side_bar.php' ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <ol class="breadcrumb breadcrumb-bg-teal align-right" style="background-color:#333 !important;">
                <li><a href="javascript:void(0);">Inventory management</a></li>
            </ol>
            <!-- Begin - Edit Inventory Form -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="pull-left no-margin">Edit Inventory Details</h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <form method="post" enctype="multipart/form-data">
                                    <?php
                                    // Fetch data for editing if edit_id is set in GET request
                                    if (isset($_GET['edit_id'])) {
                                        // Get existing details of the inventory
                                        list($code, $quantity, $expiration) = $admin->getInventoryDetailsOfAProduct($connection, $_GET['edit_id']);
                                    }
                                    ?>
                                    <div class="body">
                                        <label>Code</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <!-- Input field for code with pre-filled value -->
                                                <input type="text" class="form-control" name="code"
                                                    value="<?php echo $code; ?>" required>
                                            </div>
                                        </div>
                                        <label>Quantity</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <!-- Input field for quantity with pre-filled value -->
                                                <input type="text" class="form-control" name="quantity"
                                                    value="<?php echo $quantity; ?>" required>
                                            </div>
                                        </div>
                                        <label>Expire Date</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <!-- Input field for expiration date with pre-filled value -->
                                                <input type="date" class="form-control" name="expiration"
                                                    value="<?php echo $expiration; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3">
                                                <!-- Submit button to update inventory -->
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                                                    name="update">Update</button>
                                                <!-- Cancel button to return to view inventory page -->
                                                <a href="view_inventory.php?view_id=<?php echo $_GET['edit_id']; ?>"
                                                    class="btn btn-danger m-t-15 waves-effect">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End - Edit Inventory Form -->
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

<!-- PHP script to update data in database -->
<?php
if (isset($_POST['update'])) {
    // Get form data
    $pr_id = $_GET['edit_id'];
    $code = $_POST['code'];
    $quantity = $_POST['quantity'];
    $expiration = $_POST['expiration'];
    $pid = $_GET['pid'];

    $admin->viewInventoryEditAProduct($connection, $pr_id, $code, $quantity, $expiration, $pid);
}
?>