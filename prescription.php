<?php
// Start the session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION["username"])) {
    header('location:loging-your-session');
    exit;
}

// Include database connection
require_once('../database/db.php');

?>
<html lang="en">

<head>
    <title>Phrime Care | Online</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Rubik:400,700|Crimson+Text:400,400i" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- SweetAlert CSS -->
    <link href="../common/plugins/sweetalert2/style.css" type="text/css" rel="stylesheet">
    <link href="../common/plugins/sweetalert2/sweetalert.css" type="text/css" rel="stylesheet">

    <!-- jQuery & SweetAlert JS -->
    <script src="../common/plugins/sweetalert2/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="../common/plugins/sweetalert2/sweetalert.min.js" type="text/javascript"></script>

</head>

<body>

<div class="site-wrap">
    <?php include 'menu.php'; ?>  

    <div class="site-section">
        <div class="container">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8 mb-5 mb-md-0">
                        <h2 class="h3 mb-3 text-black">Order with Doctor Prescription</h2>
                        <div class="p-3 p-lg-5 border">
                            <p>Upload a clear photo of the doctor prescription</p>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="text-black">Prescription <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="image" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="c_order_notes" class="text-black">Order Notes</label>
                                <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary btn-md btn-block">Request for Price</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Files -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/main.js"></script>

</body>

</html>

<?php
// Check if form is submitted
if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];

    // Get customer id
    $userQuery = "SELECT users.id AS user_id, customer_list.user_id AS c_user_id, customer_list.id AS c_id 
                  FROM users 
                  INNER JOIN customer_list ON users.id = customer_list.user_id 
                  WHERE username = '$username'";

    $userResult = mysqli_query($connection, $userQuery);
    if ($userResult && mysqli_num_rows($userResult) > 0) {
        $userRow = mysqli_fetch_assoc($userResult);
        $customer_id = $userRow['c_id'];

        $c_order_notes = $_POST['c_order_notes'];

        // Sanitize the filename
        $prescription_slip = mysqli_real_escape_string($connection, $_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $prescription_slip);

        // Insert into prescription table
        $query_order = "INSERT INTO prescription (customer_id, prescription, price) 
                        VALUES ('$customer_id', '$prescription_slip', '0')";

        if (mysqli_query($connection, $query_order)) {
            // Success message with SweetAlert
            echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "SUCCESS...!",
                            text: "Prescription uploaded successfully.",
                            type: "success"
                        }, function() {
                            window.location = "prescription_list.php";
                        });
                    }, 100);
                </script>';
        } else {
            // SQL Error
            echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "ERROR",
                            text: "Failed to place order. SQL Error: ' . mysqli_error($connection) . '",
                            type: "error"
                        }, function() {
                            window.location = "index.php";
                        });
                    }, 100);
                </script>';
        }
    } else {
        // Fetch user details error
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "ERROR",
                        text: "Failed to fetch user details.",
                        type: "error"
                    }, function() {
                        window.location = "index.php";
                    });
                }, 100);
            </script>';
    }
}
?>
