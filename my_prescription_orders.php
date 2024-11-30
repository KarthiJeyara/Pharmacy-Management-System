<?php
require_once('../database/db.php');
session_start();

if(!$_SESSION["username"]==true)
    {
            header('location:index.php');

    }
    $user_type = 'customer';
?>

<!DOCTYPE html>
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
</head>

<body>

    <div class="site-wrap">
        <?php include 'menu.php' ?>

        <div class="site-section">
            <div class="container">
                <div class="row mb-5">
                    <form class="col-md-12" method="post">
                        <div class="site-blocks-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="product-thumbnail">Date Ordered</th>
                                        <th class="product-name">Order Code</th>
                                        <th class="product-name">Prescription</th>
                                        <th class="product-price">Total Amount</th>
                                        <th class="product-quantity">Status</th>
                                        <th class="product-total">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    include('../database/db.php');

                                    $username = $_SESSION['username'];

                                    // Get customer id
                                    $userQuery = "SELECT users.id AS user_id, customer_list.user_id AS c_user_id, customer_list.id AS c_id FROM users INNER JOIN customer_list ON users.id = customer_list.user_id 
                                    WHERE username = '$username'";

                                    $userResult = mysqli_query($connection, $userQuery);
                                    if ($userResult) {
                                        $userRow = mysqli_fetch_assoc($userResult);
                                        $customer_id = $userRow['c_id'];

                                        error_reporting(error_reporting() & ~E_WARNING);
                                        $sql = "SELECT * FROM order_list WHERE order_list.customer_id = '{$customer_id}' AND order_list.pay_slip != '-'";

                                        $result = $connection->query($sql);

                                        if (($result->num_rows > 0)) {
                                            $index = 1;
                                            while ($row = $result->fetch_assoc()) {

                                                $date_ordered = $row['date_created'];
                                                $order_code = $row['code'];
                                                $total_amount = $row['total_amount'];
                                                $status = $row['status'];
                                    $prescription = $row['pay_slip'];

                                                $current_time = time(); // Current timestamp
                                                $order_time = strtotime($date_ordered);
                                                $time_difference = $current_time - $order_time;

                                                // Convert time difference to hours
                                                $hours_difference = $time_difference / 3600;

                                    ?>
                                                <tr>
                                                    <td><?= $index; ?></td>
                                                    <td><?= $date_ordered; ?></td>
                                                    <td><?= $order_code; ?></td>
                                                    <td class="product-thumbnail">
                      <img src="uploads/<?= $prescription ;?>" alt="Image" class="img-fluid">
                    </td>
                                                    <td><?= $total_amount; ?></td>
                                                    <td style="color: red"><?= $status; ?></td>
                                                    <td>
                                                        <form method="post">
                                                            <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                                                            <?php
                                                            if ($hours_difference <= 24) { // 24 hours
                                                                echo '<button class="btn btn-danger" name="cancel">Cancel Order</button>';
                                                            }
                                                            ?>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var hoursDifference = <?php echo $hours_difference; ?>;
                                                        var cancelButton = document.getElementById('cancelButton' + <?php echo $index; ?>);
                                                        if (hoursDifference > 24) {
                                                            cancelButton.style.display = 'none';
                                                        }
                                                    });
                                                </script>
                                    <?php
                                                $index++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No data</td></tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
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
include ('../database/db.php');

if(isset($_POST['cancel'])){

    $order_id = $_POST['order_id'];


  $sql_order_list = "DELETE FROM order_list WHERE id ='$order_id'";
  $sql_order_item = "DELETE FROM order_items WHERE order_id ='$order_id'";

  if ( (($connection->query($sql_order_list)) AND ($connection->query($sql_order_item))) ==  TRUE)
  {
   echo "<script>alert('Order canceled successfully.'); window.location.href = 'my_prescription_orders.php';</script>";
  }
  else
  {
        echo "<script>alert('Failed to cancel the order.'); window.location.href ='my_prescription_orders.php;</script>";
}  
}

?>
