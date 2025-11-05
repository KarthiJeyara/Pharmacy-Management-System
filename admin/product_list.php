<?php
require_once('../Classes/DbConnector.php');
require_once('../Classes/Administrator.php');

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

<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin | Product</title>
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

    <?php include '../common/includes/top.php' ?>

    <section>
        <?php include '../common/includes/left_side_bar.php' ?>
    </section>

    <section class="content">
        <div class="container-fluid">

            <ol class="breadcrumb breadcrumb-bg-teal align-right" style="background-color:#333 !important;">
                <li><a href="javascript:void(0);">Product Management</a></li>
            </ol>

            <!-- Category Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Details of Products
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                    <button type="button" class="btn btn-info waves-effect" data-toggle="modal"
                                        data-target="#myModal"> <i class="fa fa-user-plus m-r-5"
                                            style="color:white"></i> <span>Add New Product</span> </button>
                                </li>
                            </ul>
                        </div>


                        <!--Begin - Add new product form-->
                        <div class="modal fade center" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="pull-left no-margin">Add New Product</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card">
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="body">
                                                        <label>Brand</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control" placeholder=""
                                                                    name="brand" required>
                                                            </div>
                                                        </div>
                                                        <label>Name</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control" placeholder=""
                                                                    name="name" required>
                                                            </div>
                                                        </div>
                                                        <label>Dose</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control" placeholder=""
                                                                    name="dose" required>
                                                            </div>
                                                        </div>
                                                        <label>Category</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <select class="form-control show-tick" name="category">
                                                                    <option value=""><--Please choose--> </option>
                                                                    <?php
                                                                    $result = $admin->getCategoryList($connection);
                                                                    if ($result != "0") {
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            ?>
                                                                            <option value="<?php echo $row["id"]; ?>">
                                                                                <?php echo $row["name"]; ?>
                                                                            </option>
                                                                        <?php }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <label>Description</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control" placeholder=""
                                                                    name="description" required>
                                                            </div>
                                                        </div>
                                                        <label>Price</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control" placeholder=""
                                                                    name="price" required>
                                                            </div>
                                                        </div>
                                                        <label>Status</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <select class="form-control show-tick" name="status[]"
                                                                    multiple>
                                                                    <option value="" disabled><--Please choose-->
                                                                    </option>
                                                                    <option value="Badulla">Badulla (Main Branch)
                                                                    </option>
                                                                    <option value="Kandy">Kandy</option>
                                                                    <option value="Gampaha">Gampaha</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <label>Image of product</label>
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input name="image" type="file" onchange="img001(this);"
                                                                    required>
                                                                <img style="width: 100px;height:auto" id="img01" src="#"
                                                                    alt="" />
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
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3">
                                                <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                                                    name="submit">Save</button>
                                                <a href="product_list.php"><button type="button"
                                                        class="btn btn-danger m-t-15 waves-effect">Cancel</button></a>
                                            </div>
                                        </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--##END - form-->

                        <div class="body">
                            <div class="table-responsive">
                                <div style="text-align: right;padding: 10px;"></div>
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Name</th>
                                            <th style="text-align: center;">Category Name</th>
                                            <th style="text-align: center;">Brand</th>
                                            <th style="text-align: center;">Dose</th>
                                            <th style="text-align: center;">Price</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Name</th>
                                            <th style="text-align: center;">Category Name</th>
                                            <th style="text-align: center;">Brand</th>
                                            <th style="text-align: center;">Dose</th>
                                            <th style="text-align: center;">Price</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $result = $admin->productListGetDetails($connection);
                                        if (($result != "0")) {
                                            $index = 1;
                                            while ($row = $result->fetch_assoc()) {

                                                $id = $row['p_id'];
                                                $p_name = $row['p_name'];
                                                $p_description = $row['p_description'];
                                                $p_brand = $row['p_brand'];
                                                $p_dose = $row['p_dose'];
                                                $p_price = $row['p_price'];
                                                $p_status = $row['p_status'];
                                                $c_name = $row['c_name'];

                                                ?>

                                                <tr>
                                                    <td><?= $index; ?></td>
                                                    <td><?= $p_name; ?></td>
                                                    <td><?= $c_name; ?></td>
                                                    <td><?= $p_brand; ?></td>
                                                    <td><?= $p_dose; ?></td>
                                                    <td><?= $p_price; ?></td>
                                                    <td style="color:purple; font-weight: bold"><?= $p_status; ?></td>
                                                    <td>
                                                        <a href="view_product.php?view_id=<?= $row['p_id']; ?>">
                                                            <button type="button"
                                                                class="btn btn-success btn-circle waves-effect waves-circle waves-float">
                                                                <i class="fa fa fa-eye center"></i>
                                                            </button>
                                                        </a>
                                                        <a href="edit_product.php?edit_id=<?= $row['p_id']; ?>">
                                                            <button type="button"
                                                                class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                                <i class="fa fa fa-pencil"></i>
                                                            </button>
                                                        </a>
                                                        <a href="javascript:delete_id(<?= $row['p_id']; ?>)">
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

    <script type="text/JavaScript">
        function delete_id(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                // Proceed with deletion, e.g., by redirecting or making an AJAX call
                window.location.href='product_list.php?delete_id='+id;
            }
        }
    </script>
</body>

</html>

<!-- Insert product data to databse -->
<?php

if (isset($_POST['submit'])) {
    $brand = $_POST['brand'];
    $name = $_POST['name'];
    $dose = $_POST['dose'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle multiple status values
    if (isset($_POST['status'])) {
        $status_array = $_POST['status'];
        $status = implode(',', $status_array);
    } else {
        $status = '';
    }

    $image = $_FILES["image"]["name"];
    move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image);

    // Insert value into product_list table
    $admin->productListInsertANewProduct($connection, $category, $brand, $name, $description, $dose, $price, $image, $status);
}
?>
<!-- End inserting data to database -->

<!-- delete product records-->
<?php
if (isset($_GET['delete_id'])) {
    $admin->productListDeleteProduct($connection, $_GET['delete_id']);
}

?>