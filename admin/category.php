<?php
// Include the database connection file
require_once('../Classes/Administrator.php');
require_once('../Classes/DbConnector.php');

// Check if the username is set in the session, if not, redirect to login page
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
$conenction = (new DbConnector())->getConnection();


// Define the user type
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

    <!-- Include top navigation bar -->
    <?php include '../common/includes/top.php' ?>

    <section>
        <!-- Include left side bar -->
        <?php include '../common/includes/left_side_bar.php' ?>
    </section>

    <section class="content">
        <div class="container-fluid">

            <ol class="breadcrumb breadcrumb-bg-teal align-right" style="background-color:#333 !important;">
                <li><a href="javascript:void(0);">Category</a></li>
            </ol>

            <!-- Category Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Details of Category
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                    <!-- Button to open the modal for adding a new category -->
                                    <button type="button" class="btn btn-info waves-effect" data-toggle="modal"
                                        data-target="#myModal">
                                        <i class="fa fa-user-plus m-r-5" style="color:white"></i>
                                        <span>Add New Category</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Begin - Add new category form -->
                        <div class="modal fade center" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="pull-left no-margin">Add New Category</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card">
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="body">
                                                        <label>Name</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <!-- Input for category name -->
                                                                <input type="text" class="form-control" placeholder=""
                                                                    name="name" required>
                                                            </div>
                                                        </div>
                                                        <label>Description</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <!-- Input for category description -->
                                                                <input type="text" class="form-control" placeholder=""
                                                                    name="description" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3">
                                                <!-- Submit button to save the new category -->
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                                                    name="submit">Save</button>
                                                <!-- Button to cancel and close the modal -->
                                                <a href="category.php"><button type="button"
                                                        class="btn btn-danger m-t-15 waves-effect">Cancel</button></a>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ##END - Add new category form -->

                        <div class="body">
                            <div class="table-responsive">
                                <div style="text-align: right;padding: 10px;"></div>
                                <!-- Table displaying category details -->
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Name</th>
                                            <th style="text-align: center;">Description</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Name</th>
                                            <th style="text-align: center;">Description</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        // Include the database connection file
                                        // include('../database/db.php');
                                        $connection = (new DbConnector())->getConnection();
                                        $result = $admin->getCategoryList($connection);
                                        if ($result != "0") {
                                            $index = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                $id = $row['id'];
                                                $name = $row['name'];
                                                $description = $row['description'];
                                                ?>
                                                <tr>
                                                    <td><?= $index; ?></td>
                                                    <td><?= $name; ?></td>
                                                    <td><?= $description; ?></td>
                                                    <td>
                                                        <!-- Link to edit the category -->
                                                        <a href="edit_category.php?edit_id=<?= $row['id']; ?>">
                                                            <button type="button"
                                                                class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                                <i class="fa fa fa-pencil"></i>
                                                            </button>
                                                        </a>
                                                        <!-- Link to delete the category -->
                                                        <a href="javascript:delete_id(<?= $row['id']; ?>)">
                                                            <button type="button"
                                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                                                                <i class="fa  fa-trash-o"></i>
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $index++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Category Table -->
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

<!-- Insert category data to database -->
<?php
// Include the database connection file
include_once '../database/db.php';

// Check if the submit button was clicked
if (isset($_POST['submit'])) {

    // Retrieve the category name and description from the form
    $name = $_POST['name'];
    $description = $_POST['description'];

    $admin->addCategory($connection, $name, $description);
}
?>
<!-- End inserting data to database -->

<!-- delete category records -->
<?php
// Check if the delete_id is set in the GET request
if (isset($_GET['delete_id'])) {
    // Delete the category with the specified ID
    $admin->deleteCategory($connection, $_GET['delete_id']);
}
?>

<!-- JavaScript function to confirm deletion -->
<script type="text/JavaScript">
function delete_id(id) {
    if (confirm('Sure to remove this record?')) {
        window.location.href = 'category.php?delete_id=' + id;
    }
}
</script>