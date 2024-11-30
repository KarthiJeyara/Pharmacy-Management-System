<?php require_once('../database/db.php'); 
session_start();
                         
if(!$_SESSION["username"]==true)
    {
            header('location:../login/index.php');                             
    }

     $user_type = 'admin';
                                
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin | Inventory</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../common/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../common/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../common/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="../common/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">


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
                <li ><a href="javascript:void(0);">Inventory Management</a></li>
            </ol>

            <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>Product and Inventory Details</h3>    
            </div>

            <?php
                    error_reporting(error_reporting() & ~E_WARNING);
                    include_once '../database/db.php';
                    if (isset($_GET['view_id'])) 
                    {
                    $id = $_GET['view_id'];
                
                    $get_view = "SELECT 
                product_list.id AS p_id,
                product_list.name AS p_name,
                product_list.description AS p_description,
                product_list.brand AS p_brand,
                product_list.dose AS p_dose,
                product_list.price AS p_price,
                product_list.status AS p_status,
                product_list.image_path AS p_img,
                category_list.name AS c_name,
                category_list.id AS c_id
                FROM product_list INNER JOIN category_list 
                ON product_list.category_id = category_list.id WHERE product_list.id ='$id'";
                    $run_view = mysqli_query($connection, $get_view);
                    $row_view = mysqli_fetch_array($run_view);
                
                        $id =  $row_view['p_id'];
                        $p_name =  $row_view['p_name']; 
                        $p_description =  $row_view['p_description']; 
                        $p_brand =  $row_view['p_brand']; 
                        $p_dose =  $row_view['p_dose']; 
                        $p_price =  $row_view['p_price']; 
                        $p_status =  $row_view['p_status']; 
                        $c_name =  $row_view['c_name']; 
                        $img = $row_view['p_img'];
                    }
                    ?>     

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card" style="padding:10px">
                        <div class="card-body">
                            <h5 class="card-title"><b>Product Name : </b><?php echo $p_name; ?></h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Dose :</b> <?php echo $p_dose; ?></li>
                            <li class="list-group-item"><b>Brand Name :</b> <?php echo $p_brand; ?></li>
                            <li class="list-group-item"><b>Category Name : </b><?php echo $c_name; ?></li>
                            <li class="list-group-item"><b>Description : </b><?php echo $p_description; ?></li>
                            <li class="list-group-item"><b>Unit Price : </b><?php echo $p_price; ?></li>
                        </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div style="padding:10px">
                    <?php if ( $img != ''){ ?>
                                            <img style="width:auto;height:250px" id="img01" src="uploads/<?php echo  $img; ?> " alt=""/>
                                                <script>
                                                    function img001(input) {
                                                    if (input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                                    reader.onload = function (e) {
                                                    $('#img01')
                                                    .attr('src', e.target.result)};
                                                    reader.readAsDataURL(input.files[0]);
                                                    }}
                                                </script>
                                        <?php } else {  echo "no image";    ?>
                                            <img style="width: 100px;height:auto" id="img01" src="uploads/<?php echo  $img; ?> " alt=""/>
                                     
                                                <script>
                                                    function img001(input) {
                                                    if (input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                                    reader.onload = function (e) {
                                                    $('#img01')
                                                    .attr('src', e.target.result)};
                                                    reader.readAsDataURL(input.files[0]);
                                                    }}
                                            </script>
                                        <?php } ?>  
                </div>
            </div>     
        </div>  
             
            <!-- Inventory Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               All Details of Stocks
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                    <button  type="button" class="btn btn-info waves-effect" data-toggle="modal" data-target="#myModal"> <i class="fa fa-user-plus m-r-5" style="color:white"></i> <span>Add New Stock</span> </button>
                                    <a href="inventory_list.php"><button  type="button" class="btn btn-warning waves-effect"> <span>Back</span> </button></a>
                                    
                                </li>
                            </ul>
                        </div>

<!--Begin - Add new stock form--> 
<div class="modal fade center" id="myModal"> 
  <div class="modal-dialog"> 
    <div class="modal-content">
        <div class="modal-header" > 
            <h3 class="pull-left no-margin">Add New Stock</h3>    
        </div>
        <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <form method="post" enctype="multipart/form-data">    
                        <div class="body">
                            <label >Code</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" placeholder="" name="code" required>
                                    </div>
                                </div>
                            <label >Quantity</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" placeholder="" name="quantity" required>
                                    </div>
                                </div>
                            <label >Expiration</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="date"  class="form-control" placeholder="" name="expiration" required>
                                    </div>
                                </div>
                        </div>
                </div>
            </div>
        </div> 
        <div class="modal-footer"> 
            <div class="form-group"> 
                <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3"> 
                <button type="submit" class="btn btn-primary m-t-15 waves-effect" name="submit">Save</button>
                <a href="view_inventory.php?view_id=<?php echo $_GET['view_id']; ?>"><button type="button" class="btn btn-danger m-t-15 waves-effect">Cancel</button></a>
            </div> 
        </div>
                
                    </form> 
                </div> 
        </div> 
    </div> 
</div>
<!--##END - Add new stock form-->

                        <div class="body">
                            <div class="table-responsive">
                                <div style="text-align: right;padding: 10px;"></div>
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr >
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Code</th>
                                            <th style="text-align: center;">Quantity</th>
                                            <th style="text-align: center;">Expiration</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: center;">ID</th>
                                            <th style="text-align: center;">Code</th>
                                            <th style="text-align: center;">Quantity</th>
                                            <th style="text-align: center;">Expiration</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
<?php
    include ('../database/db.php');
    if (isset($_GET['view_id'])) 
        {
        $id = $_GET['view_id'];
        error_reporting(error_reporting() & ~E_WARNING);
        $sql = "SELECT 
                stock_list.id AS s_id,
                stock_list.code AS s_code,
                stock_list.quantity AS s_quantity,
                stock_list.expiration AS s_expiration,
                product_list.id AS p_id
                FROM stock_list INNER JOIN product_list 
                ON stock_list.product_id = product_list.id WHERE stock_list.product_id ='$id'";

    $result = $connection->query( $sql);

    if ( ($result-> num_rows > 0)  ) 
            {
            $index = 1;
            while ( $row = $result->fetch_assoc() ) 
                    {
                        $id =  $row['s_id'];
                        $product_id =  $row['p_id']; 
                        $code =  $row['s_code']; 
                        $quantity =  $row['s_quantity']; 
                        $expiration =  $row['s_expiration'];                        
?>
           
                                        <tr>
                                            <td style="text-align:right;"><?= $index ;?></td>
                                            <td style="text-align:right;"><?= $code ;?></td>
                                            <td style="text-align:right;"><?= $quantity ;?></td>
                                            <td style="text-align:right;"><?= $expiration ;?></td>                       
                                            <td style="text-align:right;">
                                                <a href="edit_inventory.php?edit_id=<?=$row['s_id'];?>">
                                                    <button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                        <i class="fa fa fa-pencil"></i>
                                                    </button>
                                                </a>
                                                <a href="javascript:delete_id(<?=$row['s_id'];?>)">
                                                    <button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                                                        <i class="fa  fa-trash-o"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
<?php $index++ ;} } } ?>
        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Inventory Table -->
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

<!-- Insert stock data to databse -->
<?php

include_once '../database/db.php';

if (isset($_POST['submit'])) {

    $code = $_POST['code'];
    $quantity  = $_POST['quantity'];
    $expiration = $_POST['expiration'];
    $product_id = $_GET['view_id'];
  
    // insert value stock_list table
    
   $quary = "INSERT INTO stock_list(product_id,code,quantity,expiration) VALUES ('$product_id','$code','$quantity','$expiration')";

  
    if (mysqli_query($connection, $quary)   )
                    {
                        $successStatus = 1;
                     } 
                     else 
                     {
                         echo "Error: " . mysqli_error($connection);
                         $successStatus = 0;
                    }



    if ($successStatus==1)

            {
                echo '<script>
                    setTimeout(function() {
                    swal({
                    title: "SUCCESS...!",
                    text: "",
                    type: "success"
                    }, function() {
                    window.location = "view_inventory.php?view_id=' . $product_id . '";
                    });
                    },100);
                    </script>';
            }

    else
            {
                    echo '<script>
                    setTimeout(function() {
                    swal({
                    title: "ERROR",
                    text: "",
                    type: "error"
                    }, function() {
                    window.location = "view_inventory.php?view_id=' . $product_id . '";
                    });
                    },100);
                    </script>';
            }
    
        }

?>
<!-- End inserting data to database -->

<!-- delete records-->
<?php
include('../database/db.php');

if (isset($_GET['delete_id'])) {
    $stock_id = $_GET['delete_id'];
    $view_id = $_GET['view_id'];

    $sql = "DELETE FROM stock_list WHERE id = $stock_id";
    if ($connection->query($sql) === TRUE) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "SUCCESS!",
                    text: "",
                    type: "success"
                }, function() {
                    window.location = "view_inventory.php?view_id=' . $view_id . '";
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
                    window.location = "view_inventory.php?view_id=' . $view_id . '";
                });
            }, 100);
        </script>';
    }
}
?>


<script type="text/javascript">
function delete_id(stock_id) {
    var view_id = "<?php echo $_GET['view_id']; ?>";
    if (confirm('Are you sure you want to remove this record?')) {
        window.location.href = 'view_inventory.php?delete_id=' + stock_id + '&view_id=' + view_id;
    }
}
</script>

<!-- -->



