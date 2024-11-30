<?php 
require_once '../Classes/DbConnector.php';
require_once '../Classes/Pharmacist.php';

if (!isset($_SESSION["username"])) {
    header('location:../login/index.php');
    exit();
} else {
    $username = $_SESSION["username"];
}

$pharmacist = new Pharmacist($username, null);
$connection = (new DbConnector())->getConnection();

$user_type = 'pharmacist';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Pharmacist | Edit My Details</title>
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
                <li ><a href="javascript:void(0);">Pharmacist | Edit My Details</a></li>
            </ol>
             
<!-- Begin - form --> 
<div class=""> 
  <div class="modal-dialog"> 
    <div class="modal-content">
        <div class="modal-header" > 
            <h3 class="pull-left no-margin">Edit Profile</h3>    
        </div>
        <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <form method="post" enctype="multipart/form-data"> 
                    <?php
                    if (isset($_GET['edit_id'])) {
                        $id = $_GET['edit_id'];
                        $get_edit = "SELECT * FROM users WHERE id ='$id'";
                        $run_edit = mysqli_query($connection, $get_edit);
                        $row_edit = mysqli_fetch_array($run_edit);
                    
                        $f_name = $row_edit['firstname'];
                        $m_name = $row_edit['middlename']; 
                        $l_name = $row_edit['lastname']; 
                        $username = $row_edit['username']; 
                        $password = $row_edit['password']; 
                    }
                    ?>     
                        <div class="body">
                            <label >First Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  class="form-control" name="f_name" value="<?php echo $f_name; ?>" >
                                </div>
                            </div>
                            <label >Middle Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  class="form-control" name="m_name" value="<?php echo $m_name; ?>" >
                                </div>
                            </div>
                            <label >Last Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  class="form-control" name="l_name" value="<?php echo $l_name; ?>" >
                                </div>
                            </div>
                            <label >User Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  class="form-control" name="u_name" value="<?php echo $username; ?>" >
                                </div>
                            </div>
                            <label >Password</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password"  class="form-control" name="password" value="<?php echo $password; ?>">
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
                    <a href="employee.php"><button type="button" class="btn btn-danger m-t-15 waves-effect">Cancel</button></a>
                </div> 
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

<!-- update data to database -->
<?php
if (isset($_POST['update'])) {
    $pr_id = $_GET['edit_id']; 

    $m_name  = $_POST['m_name'];
    $l_name = $_POST['l_name'];
    $f_name = $_POST['f_name']; 
    $u_name = $_POST['u_name'];
    $new_password = $_POST['password'];

    // Fetch the current hashed password from the database
    $get_password_query = "SELECT password FROM users WHERE id='$pr_id'";
    $result = mysqli_query($connection, $get_password_query);
    $row = mysqli_fetch_array($result);
    $current_hashed_password = $row['password'];

    // Check if the input password is different from the current hashed password
    if ($new_password !== $current_hashed_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        // Keep the current hashed password
        $hashed_password = $current_hashed_password;
    }

    $update = "UPDATE users SET firstname='$f_name', middlename='$m_name', lastname='$l_name', username='$u_name', password='$hashed_password' WHERE id='$pr_id'";

    if (mysqli_query($connection, $update)) {
        $successStatus = 1;
    } else {
        echo "Error: " . mysqli_error($connection);
        $successStatus = 0;
    }

    if ($successStatus == 1) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "SUCCESS!",
                    text: "",
                    type: "success"
                }, function() {
                    window.location = "p_profile.php";
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
                    window.location = "p_profile.php";
                });
            }, 100);
        </script>';
    }
}
?>
<!-- End update data to database -->
