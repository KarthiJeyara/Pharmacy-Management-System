<?php
require_once('../database/db.php');
session_start();

if(!$_SESSION["username"]==true) {
    header('location:loging-your-session');
}

$user_type = 'customer';

error_reporting(error_reporting() & ~E_WARNING);

// Handle deletion of a prescription
if (isset($_POST['delete'])) {
    $id = $_POST['id']; // Retrieve the id from the form
    $deleteQuery = "DELETE FROM prescription WHERE id = '{$id}'"; // Use the id to delete the row
    mysqli_query($connection, $deleteQuery);
    header("Location: index.php");
    exit();
}
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
                  <th class="product-price">Prescription</th>
                  <th class="product-total">Price</th>
                  <th class="product-remove">Remove</th>
                </tr>
              </thead>
              <tbody>

                <?php
                include ('../database/db.php');

                $username = $_SESSION['username'];

                $userQuery = "SELECT users.id AS user_id, 
                                  customer_list.user_id AS c_user_id, 
                                  customer_list.id AS c_id 
                                  FROM users 
                                  INNER JOIN 
                                  customer_list ON users.id = customer_list.user_id 
                                  WHERE username= '$username'";

                $userResult = mysqli_query($connection, $userQuery);
                $userRow = mysqli_fetch_assoc($userResult);
                $customer_id = $userRow['c_id'];

                error_reporting(error_reporting() & ~E_WARNING);
                $sql = "SELECT * FROM prescription WHERE customer_id = '{$customer_id}'";
                $result = $connection->query($sql);

                if ($result->num_rows > 0) {
                    $index = 1;
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['id'];
                        $prescription = $row['prescription'];
                        $price = $row['price'];
                ?>
                  <tr>
                    <td><?= $index; ?></td>
                    <td class="product-thumbnail">
                      <img src="uploads/<?= $prescription; ?>" alt="Image" class="img-fluid">
                    </td>
                    <td class="product-name">
                      <h2 class="h5 text-black"><?= $price; ?></h2>
                    </td>
                    <td>
                      <form method="post" action="">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" name="delete" class="btn btn-primary height-auto btn-sm">X</button>
                      </form>
                    </td>
                  </tr>
                <?php
                    $index++;
                    }
                } else {
                    echo "<tr><td colspan='7'>No data</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>

      <?php if ($result->num_rows > 0 && $price != '0') { ?>
        <div class="row">
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-6">
                <a href="index.php"><button class="btn btn-outline-primary btn-md btn-block">Continue Shopping</button></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 pl-5">
            <div class="row justify-content-end">
              <div class="col-md-7">
                <div class="row">
                  <div class="col-md-12">
                    <button class="btn btn-primary btn-lg btn-block" onclick="window.location='online_payment_prescription.php'">Proceed To Checkout</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
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

  ?>