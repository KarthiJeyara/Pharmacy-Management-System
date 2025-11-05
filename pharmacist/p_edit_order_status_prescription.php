<?php
// require_once('../database/db.php'); // Include database connection

require_once('../Classes/DbConnector.php');
require_once('../Classes/Pharmacist.php');

// Check if user is not logged in, redirect to login page
if (!$_SESSION["username"] == true) {
    header('location:../login/index.php');
} else {
    $username = $_SESSION["username"];
    $type = $_SESSION["type"];
    if ($type != 'pharmacist') {
        header('location:../login/index.php');
    }
}

$pharmacist = new Pharmacist($username, null);
$connection = (new DbConnector())->getConnection();

$user_type = 'pharmacist'; // Define user type
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Parmacist | Edit Order - Prescription</title>
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

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="../common/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

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
                <li ><a href="javascript:void(0);">Orders</a></li>
            </ol>
             
            
                        


<!--Begin - Add new category form--> 
<div class=""> 
  <div class="modal-dialog"> 
    <div class="modal-content">
        <div class="modal-header" > 
            <h3 class="pull-left no-margin">Edit Order Status|Price - Prescription</h3>    
        </div>
        <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <form method="post" enctype="multipart/form-data"> 
                    <?php
                    include_once '../database/db.php';
                    if (isset($_GET['edit_id'])) 
                    {
                    $id = $_GET['edit_id'];
                    $get_edit = "SELECT 
                                    order_list.id AS o_id, 
                                    order_list.customer_id AS o_customer_id, 
                                    order_list.total_amount AS o_total_amount, 
                                    order_list.status AS o_status, 
                                    order_list.code AS o_code, 
                                    order_list.delivery_address AS o_address
                                    FROM order_list 
                                    WHERE order_list.id ='$id'";
                    $run_edit = mysqli_query($connection, $get_edit);
                    $row_edit = mysqli_fetch_array($run_edit);
                
                        $id =  $row_edit['o_id'];
                        $code =  $row_edit['o_code']; 
                        $total_amount =  $row_edit['o_total_amount']; 
                        $status =  $row_edit['o_status']; 
                        $order_deliver_address =  $row_edit['o_address']; 
                
                    }
                    ?>     
                        <div class="body">
                            <label >Code</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" disabled value="<?php echo $code; ?>" >
                                    </div>
                                </div>
                            <label >Total Amount</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" name="price"  value="<?php echo $total_amount; ?>" >
                                    </div>
                                </div>
                            <label >Status</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="status">
                                            <option selected="" value="<?php echo $status; ?>">
                                                <?php if ($status == "pending") { ?>
                                                <?php  echo "Pending";}?> 
                                                <?php if ($status == "packed") { ?>
                                                <?php  echo "Packed";}?> 
                                                <?php if ($status == "done") { ?>
                                                <?php  echo "Done";}?> 
                                                <?php if ($status == "paid") { ?>
                                                <?php  echo "Paid";}?>
                                                <?php if ($status == "out for delivery") { ?>
                                                <?php  echo "Out for Delivery";}?> 
                                            </option>
                                            <option disabled><-- please choose, if update --></option>
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid</option>
                                            <option value="packed">Packed</option>
                                            <option value="out for delivery">Out for Delivery</option>
                                            <option value="done">Done</option>
                                        </select>
                                    </div>
                                </div>
                        </div>
                </div>
            </div>
        </div> 
        <div class="modal-footer"> 
            <div class="form-group"> 
                <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3"> 
                <button type="submit" class="btn btn-primary m-t-15 waves-effect" name="update">Update</button>
                <a href="p_order_list_prescription.php"><button type="button" class="btn btn-danger m-t-15 waves-effect">Cancel</button></a>
            </div> 
        </div>
                
                    </form> 
                </div> 
        </div> 
    </div> 
</div>
<!--##END - form--> 
            
                        
                
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

<!-- update data to databse -->
<?php
            if (isset($_POST['update'])) {
                $pr_id = $_GET['edit_id']; 
                $status = $_POST['status'];
                $price = $_POST['price'];

    $update = "UPDATE order_list SET status='$status',total_amount = '$price' WHERE id='$pr_id' ";
    
               if (mysqli_query($connection, $update))
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
                    title: "SUCCESS!",
                    text: "",
                    type: "success"
                    }, function() {
                    window.location = "p_order_list_prescription.php";
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
                    window.location = "p_order_list_prescription.php";
                    });
                    },100);
                    </script>';
            }
            }
            ?>

<!-- End update data to databse -->