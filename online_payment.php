<?php require_once('../database/db.php'); 
session_start();
                         
if (!$_SESSION["username"] == true) {
    header('location:loging-your-session');
}
$user_type = 'customer';
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

  <!-- Sweet alert -->  
  <link href="../common/plugins/sweetalert2/style.css" type="text/css" rel="stylesheet">
  <link href="../common/plugins/sweetalert2/sweetalert.css" type="text/css" rel="stylesheet">
  <script src="../common/plugins/sweetalert2/jquery-3.4.1.min.js" type="text/javascript"></script>
  <script src="../common/plugins/sweetalert2/sweetalert.min.js" type="text/javascript"></script>
  <!-- -->

</head>

<body>

<div class="site-wrap">
  <?php include 'menu.php' ?>  

  <div class="site-section">
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Payment Details</h2>
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group">
                            <label class="text-black">Card Type <span class="text-danger">*</span></label><br>
                            <input type="radio" id="mastercard" name="c_card_type" value="mastercard" required>
                            <label for="mastercard">MasterCard</label><br>
                            <input type="radio" id="visa" name="c_card_type" value="visa" required>
                            <label for="visa">Visa</label><br>
                        </div>
                        <div class="form-group">
                            <label for="c_card_name" class="text-black">Card Holder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="c_card_name" required>
                        </div>
                        <div class="form-group">
                            <label for="c_card_number" class="text-black">Card Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="c_card_number" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_card_exp_date" class="text-black">Expiration Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="c_card_exp_date" placeholder="MM/YYYY" required>
                            </div>
                            <div class="col-md-6">
                                <label for="c_card_cvv" class="text-black">CVV <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="c_card_cvv" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Section for Delivery Address -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h2 class="h3 mb-3 text-black">Delivery Address</h2>
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group">
                            <label for="c_delivery_address" class="text-black">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="c_delivery_address" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Proceed</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>

</div>

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
include_once '../database/db.php';

if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $delivery_address = $_POST['c_delivery_address'];  // Capture delivery address

    // Get customer id
    $userQuery = "SELECT users.id AS user_id, customer_list.user_id AS c_user_id, customer_list.id AS c_id 
                  FROM users 
                  INNER JOIN customer_list ON users.id = customer_list.user_id 
                  WHERE username = '$username'";

    $userResult = mysqli_query($connection, $userQuery);
    if ($userResult) {
        $userRow = mysqli_fetch_assoc($userResult);
        $customer_id = $userRow['c_id'];

        $code = random_int(1, 10000000000);

        // Get total amount from cart_list table
        $total_amount = 0;
        $cart_items = [];

        $getTotalQuery = "SELECT * FROM cart_list WHERE customer_id = '$customer_id'";
        $TotalResult = mysqli_query($connection, $getTotalQuery);

        while ($row = mysqli_fetch_assoc($TotalResult)) {
            $cart_items[] = $row;
            $total_amount += $row['quantity'] * $row['price']; // Assuming price is in cart_list
        }

        // Insert into order_list
        $query_order = "INSERT INTO order_list (code, customer_id, delivery_address, status, total_amount, pay_slip) 
                        VALUES ('$code', '$customer_id', '$delivery_address', 'paid', '$total_amount', '-')";  // Insert delivery address

        if (mysqli_query($connection, $query_order)) {
            // Retrieve the order_id of the newly inserted order
            $order_id = mysqli_insert_id($connection);

            // Insert into order_items
            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $query_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES ('$order_id', '$product_id', '$quantity', '$price')";
                mysqli_query($connection, $query_item);
            }

            // Delete cart list
            $query_del = "DELETE FROM cart_list WHERE customer_id = '$customer_id'";
            mysqli_query($connection, $query_del);

            // Success message
            echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "SUCCESS...!",
                            text: "",
                            type: "success"
                        }, function() {
                            window.location = "thankyou.html";
                        });
                    }, 100);
                </script>';
        } else {
            echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "ERROR",
                            text: "Failed to place order.",
                            type: "error"
                        }, function() {
                            window.location = "checkout.php";
                        });
                    }, 100);
                </script>';
        }
    } else {
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "ERROR",
                        text: "Failed to fetch user details.",
                        type: "error"
                    }, function() {
                        window.location = "checkout.php";
                    });
                }, 100);
            </script>';
    }
}
?>
