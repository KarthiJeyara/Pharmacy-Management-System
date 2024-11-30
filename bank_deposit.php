<?php require_once('../database/db.php'); 
session_start();
                         
if(!$_SESSION["username"]==true)
    {
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
        <form  method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="c_address"  required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="c_order_notes" class="text-black">Order Notes</label>
                            <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-5 mb-md-0">
                    <div class="p-3 p-lg-5 border">
                        <p>Upload a clear photo of the payment slip</p>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="text-black">Pay Slip <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="image" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <span class="text-danger">NOTE: Your Name, mobile number is get from the registration for delivery details. If you want to change this, please update your profile with correct details.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-primary btn-md btn-block">Place Order</button>
                        </div>
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
include_once '../database/db.php'; // Consider removing duplicate inclusion

if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];

    // Get customer id
    $userQuery = "SELECT users.id AS user_id, customer_list.user_id AS c_user_id, customer_list.id AS c_id 
                  FROM users 
                  INNER JOIN customer_list ON users.id = customer_list.user_id 
                  WHERE username = '$username'";

    $userResult = mysqli_query($connection, $userQuery);
    if ($userResult) {
        $userRow = mysqli_fetch_assoc($userResult);
        $customer_id = $userRow['c_id'];

        $c_address = $_POST['c_address'];
        $c_order_notes = $_POST['c_order_notes'];
        $bank_slip = $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $bank_slip);
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
                        VALUES ('$code', '$customer_id', '$c_address', 'pending', '$total_amount', '$bank_slip')";

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
