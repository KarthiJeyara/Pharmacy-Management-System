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
    <title>Pharmacist | My profile</title>
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
        <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">

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
                <li ><a href="javascript:void(0);">My Details</a></li>
            </ol>
             
            
                        


<!--Begin --> 
<div class=""> 
  <div class="modal-dialog"> 
    <div class="modal-content">
        <div class="modal-header" > 
            <h3 class="pull-left no-margin">My Details</h3>    
        </div>
        <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <form method="post" enctype="multipart/form-data"> 
                    <?php
                    include_once '../database/db.php'; 
                    require_once '../Classes/Pharmacist.php';
                    
                   
                    $get_id = $_SESSION["username"];
                    $get_view = "SELECT * FROM users WHERE username ='$get_id'";
                    $run_view = mysqli_query($connection, $get_view);
                    $row_view = mysqli_fetch_array($run_view);
                
                        $id =  $row_view['id'];
                        $firstname =  $row_view['firstname']; 
                        $middlename =  $row_view['middlename']; 
                        $lastname =  $row_view['lastname']; 
                        $username =  $row_view['username'];  
                        $type =  $row_view['type']; 
                        $avatar =  $row_view['avatar']; 
                
                    ?>     
                        <div class="body">
                            <label >Image</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <?php if ( $avatar != ''){ ?>
                                            <img style="width: 100px;height:auto" id="img01" src="<?php echo  $avatar; ?> " alt=""/>
                                     
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
                                            <img style="width: 100px;height:auto" id="img01" src="<?php echo  $avatar; ?> " alt=""/>
                                     
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
                            <label >Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="text" ><?php echo $firstname;?> 
                                        <?php echo $middlename; ?> <?php echo $lastname; ?></div>
                                    </div>
                                </div>
                            <label >User Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="text" ><?php echo $username; ?></div>
                                    </div>
                                </div>
                            <label >Type</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="text" ><?php echo $type; ?></div>
                                    </div>
                                </div>
                        </div>
                </div>
            </div>
        </div> 
        <div class="modal-footer"> 
            <div class="form-group"> 
                <div class="col-sm-offset-3 col-sm-6 col-sm-offset-3"> 
                <a href="p_profile_edit.php?edit_id=<?=$row_view['id'];?>"><button type="button" class="btn btn-danger m-t-15 waves-effect">Update Profile</button></a>
            </div> 
        </div>
                
                    </form> 
                </div> 
        </div> 
    </div> 
</div>
<!--##END--> 
            
                        
                
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

