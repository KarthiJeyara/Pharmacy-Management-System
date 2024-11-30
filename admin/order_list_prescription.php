<?php
require_once '../Classes/dbConnector.php';
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

$connection = (new dbConnector())->getConnection();
$admin = new Administrator($username, null);

$user_type = 'admin';

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

    <style>
        .prescription-zoom {
            cursor: pointer;
            transition: transform 0.2s;
            /* Animation */
        }

        .prescription-zoom:hover {
            transform: scale(1.5);
            /* Zoom effect */
            z-index: 10;
            /* Ensure it appears above other elements */
        }

        .modal-body img {
            width: 100px;
            /* Thumbnail size */
            height: auto;
            transition: transform 0.2s;
            /* Animation */
        }

        .modal-body img:hover {
            transform: scale(1.5);
            /* Zoom effect on hover */
            z-index: 10;
            /* Ensure it appears above other elements */
        }
    </style>


</head>

<body class="theme-cyan">

    <?php include '../common/includes/top.php' ?>

    <section>
        <?php include '../common/includes/left_side_bar.php' ?>
    </section>

    <section class="content">
        <div class="container-fluid">

            <ol class="breadcrumb breadcrumb-bg-teal align-right" style="background-color:#333 !important;">
                <li><a href="javascript:void(0);">Order Management</a></li>
            </ol>

            <!-- Category Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Details of Orders
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                    <button type="button" class="btn btn-warning waves-effect" data-toggle="modal"
                                        data-target="#myModal"> <i class="fa fa-plus m-r-5" style="color:white"></i>
                                        <span>Price requested Prescriptions</span> </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Price Requested Prescription Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Price Requested Prescriptions</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Image</th>
                                                        <th>Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $prescription_result = $admin->getPresctiptionDetails($connection);

                                                    if ($prescription_result != "0") {
                                                        while ($prescription_row = $prescription_result->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                                <td><?= $prescription_row['id']; ?></td>
                                                                <td>
                                                                    <div class="prescription-zoom">
                                                                        <img id="img<?= $prescription_row['id']; ?>"
                                                                            src="../customer/uploads/<?= htmlspecialchars($prescription_row['prescription']); ?>"
                                                                            alt="" />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="price[<?= $prescription_row['id']; ?>]"
                                                                        value="<?= $prescription_row['price']; ?>">
                                                                </td>
                                                                <td>
                                                                    <button type="submit" name="update_price"
                                                                        value="<?= $prescription_row['id']; ?>"
                                                                        class="btn btn-success">Update</button>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='5'>No prescriptions found.</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- -->
                        <div class="body">
                            <div class="table-responsive">
                                <div style="text-align: right;padding: 10px;"></div>
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Code</th>
                                            <th style="text-align: center;">Customer</th>
                                            <th style="text-align: center;">Total Amount</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Code</th>
                                            <th style="text-align: center;">Customer</th>
                                            <th style="text-align: center;">Total Amount</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $result = $admin->getOrderListPrescriptions($connection);
                                        if ($result != "0") {
                                            $index = 1;
                                            while ($row = $result->fetch_assoc()) {

                                                $id = $row['o_id'];
                                                $code = $row['o_code'];
                                                $customer_firstname = $row['c_firstname'];
                                                $total_amount = $row['o_total_amount'];
                                                $status = $row['o_status'];

                                                ?>

                                                <tr>
                                                    <td><?= $index; ?></td>
                                                    <td><?= $code; ?></td>
                                                    <td><?= $customer_firstname; ?></td>
                                                    <td><?= $total_amount; ?></td>
                                                    <td>
                                                        <?php if ($status == "pending") { ?>
                                                            <span class="btn-warning" style="padding:5px"><?php echo "pending";
                                                        } ?>
                                                        </span>
                                                        <?php if ($status == "packed") { ?>
                                                            <span class="btn-info" style="padding:5px"><?php echo "packed";
                                                        } ?>
                                                        </span>
                                                        <?php if ($status == "out for delivery") { ?>
                                                            <span class="btn-success" style="padding:5px"><?php echo "out for delivery";
                                                        } ?> </span>
                                                        <?php if ($status == "paid") { ?>
                                                            <span class="btn-danger" style="padding:5px"><?php echo "paid";
                                                        } ?>
                                                        </span>
                                                        <?php if ($status == "done") { ?>
                                                            <span class="btn-primary" style="padding:5px"><?php echo "Done";
                                                        } ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="view_order_prescription.php?view_id=<?= $row['o_id']; ?>">
                                                            <button type="button"
                                                                class="btn btn-success btn-circle waves-effect waves-circle waves-float">
                                                                <i class="fa fa fa-eye center"></i>
                                                            </button>
                                                        </a>
                                                        <a
                                                            href="edit_order_status_prescription.php?edit_id=<?= $row['o_id']; ?>">
                                                            <button type="button"
                                                                class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                                <i class="fa fa fa-pencil"></i>
                                                            </button>
                                                        </a>
                                                        <a href="javascript:delete_id(<?= $row['o_id']; ?>)">
                                                            <button type="button"
                                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                                                                <i class="fa  fa-trash-o"></i>
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $index++;
                                            }
                                        } ?>

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

<!-- delete records-->
<?php
// include('../database/db.php');

// $sql = "select * from order_list";
// $result = $connection->query($sql);

if (isset($_GET['delete_id'])) {

    $admin->deleteOrderPerscription($connection, $_GET['delete_id']);
}

?>

<script type="text/JavaScript">


function delete_id(id){
  if(confirm('sure to remove this record?')){
    window.location.href='order_list_prescription.php?delete_id='+id;
  }
}

</script>

<?php
if (isset($_POST['update_price'])) {
    $id = intval($_POST['update_price']);
    $newPrice = floatval($_POST['price'][$id]);

    $admin->getOrderListPrescriptionPriceUpdate($connection, $id, $newPrice);
}
?>