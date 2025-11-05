<?php
// Include the database connection file

// Start the session
session_start();

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

// require_once('../database/db.php');
require_once('../Classes/DbConnector.php');
require_once('../Classes/Administrator.php');

$connection = (new DbConnector())->getConnection();
$admin = new Administrator($username, null);

// Define the user type
$user_type = 'admin';
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Admin Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../common/plugins/font-awesome/css/font-awesome.min.css">

    <!-- Bootstrap Core Css -->
    <link href="../common/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../common/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../common/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css -->
    <link href="../common/plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../common/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="../common/css/themes/all-themes.css" rel="stylesheet" />

    <!-- Loading bar -->
    <link href="../common/plugins/loading-bar/jquery.incremental-counter.css" rel="stylesheet" type="text/css">
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
                <li class="active"><a href="javascript:void(0);">Admin Dashboard</a></li>
            </ol>

            <!-- Widgets -->
            <div class="row clearfix">
                <!-- Include top navigation bar again (seems redundant) -->
                <?php include '../common/includes/top.php' ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <!-- Widget for Categories count -->
                    <div class="info-box bg-blue hover-zoom-effect" style="margin-top: 30px; border-radius: 15px;">
                        <div class="icon">
                            <i class="fa fa-angle-double-right"></i>
                        </div>
                        <div class="content">
                            <div class="text">Categories</div>
                            <div class="number">
                                <?php
                                echo $admin->getCategoryCount($connection);
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Widget for Pending Orders count -->
                    <div class="info-box bg-grey hover-zoom-effect" style="margin-top: 10px; border-radius: 15px;">
                        <div class="icon">
                            <i class="fa fa-angle-double-right"></i>
                        </div>
                        <div class="content">
                            <div class="text">Paid Orders</div>
                            <div class="number">
                                <?php
                                echo $admin->getPaidOrderCount($connection);
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Widget for Out of Delivery Orders count -->
                    <div class="info-box bg-green hover-zoom-effect" style="margin-top: 10px; border-radius: 15px;">
                        <div class="icon">
                            <i class="fa fa-angle-double-right"></i>
                        </div>
                        <div class="content">
                            <div class="text">Out for Delivery</div>
                            <div class="number">
                                <?php
                                echo $admin->getOutForDeliveryOrderCount($connection);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <!-- Widget for Products count -->
                    <div class="info-box bg-orange hover-zoom-effect" style="margin-top: 50px; border-radius: 15px;">
                        <div class="icon">
                            <i class="fa fa-angle-double-right"></i>
                        </div>
                        <div class="content">
                            <div class="text">Products</div>
                            <div class="number">
                                <?php
                                echo $admin->getProductCount($connection);
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Widget for Packed Orders count -->
                    <div class="info-box bg-purple hover-zoom-effect" style="margin-top: 10px; border-radius: 15px;">
                        <div class="icon">
                            <i class="fa fa-angle-double-right"></i>
                        </div>
                        <div class="content">
                            <div class="text">Packed Orders</div>
                            <div class="number">
                                <?php
                                echo $admin->getPackedOrderCount($connection);
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Widget for Completed Orders count -->
                    <div class="info-box bg-red hover-zoom-effect" style="margin-top: 10px; border-radius: 15px;">
                        <div class="icon">
                            <i class="fa fa-angle-double-right"></i>
                        </div>
                        <div class="content">
                            <div class="text">Completed Orders</div>
                            <div class="number">
                                <?php
                                echo $admin->getCompletedOrderCount($connection);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Include clock widget -->
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <?php include '../common/includes/clock.php' ?>
                </div>
            </div>
        </div>
        <!-- #END# Widgets -->
    </section>

    <!-- Bootstrap Core Js -->
    <script src="../common/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../common/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../common/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../common/plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../common/plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../common/plugins/raphael/raphael.min.js"></script>
    <script src="../common/plugins/morrisjs/morris.js"></script>

    <!-- Jquery Knob Plugin Js -->
    <script src="../common/plugins/jquery-knob/jquery.knob.min.js"></script>

    <!-- Custom Js -->
    <script src="../common/js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="../common/js/demo.js"></script>

    <!-- Jquery Core Js -->
    <script src="../common/plugins/jquery/jquery.min.js"></script>

    <!-- Custom Js -->
    <script src="../common/js/admin.js"></script>

</body>

</html>