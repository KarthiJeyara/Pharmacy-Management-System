<?php 
require_once('../database/db.php'); 
session_start();

// Ensure user is logged in
if (!$_SESSION["username"] == true) {
    header('location:../login/index.php');
}

// Class for Order operations
class OrderManagement {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Fetch all orders
    public function fetchAllOrders() {
        $sql = "SELECT 
                    order_list.id AS o_id, 
                    order_list.customer_id AS o_customer_id, 
                    order_list.total_amount AS o_total_amount, 
                    order_list.status AS o_status, 
                    order_list.code AS o_code, 
                    customer_list.firstname AS c_firstname, 
                    customer_list.middlename AS c_middlename 
                FROM 
                    order_list 
                INNER JOIN 
                    customer_list 
                ON 
                    order_list.customer_id = customer_list.id 
                WHERE 
                    order_list.pay_slip = '-'";
        $result = $this->connection->query($sql);
        return $result;
    }

    // Delete an order
    public function deleteOrder($id) {
        $sql = "DELETE FROM order_list WHERE id = $id";
        return $this->connection->query($sql);
    }
}

// Class for rendering HTML
class View {
    // Render orders table
    public function renderOrdersTable($orders) {
        $index = 1;
        while ($row = $orders->fetch_assoc()) {
            $id = $row['o_id'];
            $code = $row['o_code'];
            $customer_firstname = $row['c_firstname'];
            $total_amount = $row['o_total_amount'];
            $status = $row['o_status'];
            $this->renderOrderRow($index, $id, $code, $customer_firstname, $total_amount, $status);
            $index++;
        }
    }

    // Render individual order row
    private function renderOrderRow($index, $id, $code, $customer, $amount, $status) {
        echo "<tr>
                <td>$index</td>
                <td>$code</td>
                <td>$customer</td>
                <td>$amount</td>
                <td>" . $this->renderStatus($status) . "</td>
                <td>
                    <a href='view_order.php?view_id=$id'>
                        <button type='button' class='btn btn-success btn-circle waves-effect waves-circle waves-float'>
                            <i class='fa fa-eye center'></i>
                        </button>
                    </a>
                    <a href='edit_order_status.php?edit_id=$id'>
                        <button type='button' class='btn btn-warning btn-circle waves-effect waves-circle waves-float'>
                            <i class='fa fa-pencil'></i>
                        </button>
                    </a>
                    <a href='javascript:delete_id($id)'>
                        <button type='button' class='btn btn-danger btn-circle waves-effect waves-circle waves-float'>
                            <i class='fa fa-trash-o'></i>
                        </button>
                    </a>
                </td>
              </tr>";
    }

    // Render order status
    private function renderStatus($status) {
        switch ($status) {
            case 'pending':
                return "<span class='btn-warning' style='padding:5px'>pending</span>";
            case 'packed':
                return "<span class='btn-info' style='padding:5px'>packed</span>";
            case 'out for delivery':
                return "<span class='btn-success' style='padding:5px'>out for delivery</span>";
            case 'paid':
                return "<span class='btn-danger' style='padding:5px'>paid</span>";
            case 'done':
                return "<span class='btn-primary' style='padding:5px'>Done</span>";
            default:
                return "<span>unknown</span>";
        }
    }
}

// Handle the deletion and rendering logic
$orderManager = new OrderManagement($connection);
$view = new View();

if (isset($_GET['delete_id'])) {
    if ($orderManager->deleteOrder($_GET['delete_id'])) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "SUCCESS!",
                    text: "",
                    type: "success"
                }, function() {
                    window.location = "order_list.php";
                });
            }, 100);
        </script>';
    } else {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "ERROR",
                    text: "",
                    type: "error"
                }, function() {
                    window.location = "order_list.php";
                });
            }, 100);
        </script>';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin | Orders</title>
    <!-- Load CSS & JS dependencies -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="../common/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../common/plugins/node-waves/waves.css" rel="stylesheet" />
    <link href="../common/plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="../common/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../common/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <link href="../common/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="../common/plugins/waitme/waitMe.css" rel="stylesheet" />
    <link href="../common/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <link href="../common/css/style.css" rel="stylesheet">
    <link href="../common/plugins/switchery/switchery.min.css" rel="stylesheet" />
    <link href="../common/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../common/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../common/plugins/sweetalert2/style.css" type="text/css" rel="stylesheet">
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
                <li><a href="javascript:void(0);">Order Management</a></li>
            </ol>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>All Details of Orders</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
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
                                        <?php $view->renderOrdersTable($orderManager->fetchAllOrders()); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript files -->
    <script src="../common/plugins/jquery/jquery.min.js"></script>
    <script src="../common/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../common/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script src="../common/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="../common/plugins/node-waves/waves.js"></script>
    <script src="../common/plugins/autosize/autosize.js"></script>
    <script src="../common/plugins/momentjs/moment.js"></script>
    <script src="../common/plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../common/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetime