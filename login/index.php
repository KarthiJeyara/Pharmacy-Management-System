<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Pharmacy Managemnet System</title>
   
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../common/plugins/font-awesome/css/font-awesome.min.css">

    <!-- Bootstrap Core Css -->
    <link href="../common/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../common/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../common/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../common/css/style.css" rel="stylesheet">
</head>

<body class="login-page" style="background-color: #ffffff;">
    <div class="login-box">
        <div class="card" style="border-radius: 10px;">
            <div class="body">
                <form id="sign_in" method="POST" action="login.php">
                    <div style="text-align: center; border-bottom: 1px solid #2196f3"><h4 style="color: #2196f3"><b>Prime Care Pharmacy</b> </h4> <h4 style="color: #888888">Management System</h4></div>
                    <div class="msg" style="margin-top: 20px;"></div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa  fa-user fa-2x"></i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-unlock-alt fa-2x"></i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-7">
                            <button class="btn btn-block bg-primary waves-effect" type="submit" name="login">Login</button>
                        </div>
                    </div>      
                </form>
                <hr>
                <div class="row">
                        <div class="col-xs-12"><a href="../customer/index.php"><button class="btn btn-block bg-warning waves-effect">Go to Front Page</button></div>
                        
                    </div> 
            </div>
        </div>
        
    </div>

    <!-- Jquery Core Js -->
    <script src="../common/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../common/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../common/plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="../common/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="../common/js/admin.js"></script>
    <script src="../common/js/pages/examples/sign-in.js"></script>
</body>

</html>