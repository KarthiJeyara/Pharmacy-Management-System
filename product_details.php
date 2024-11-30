<?php
require_once('../database/db.php');
session_start();
?>

<html lang="en">

<head>
  <title></title>
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
     
      <?php
        include_once '../database/db.php';
        if (isset($_GET['view_id'])) {
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
            FROM product_list 
            INNER JOIN category_list 
            ON product_list.category_id = category_list.id 
            WHERE product_list.id ='$id'";
          $run_view = mysqli_query($connection, $get_view);
          $row_view = mysqli_fetch_array($run_view);

          $p_id = $row_view['p_id'];
          $p_name = $row_view['p_name'];
          $p_description = $row_view['p_description'];
          $p_brand = $row_view['p_brand'];
          $p_dose = $row_view['p_dose'];
          $p_price = $row_view['p_price'];
          $p_status = $row_view['p_status'];
          $c_name = $row_view['c_name'];
          $img = $row_view['p_img'];
        }
      ?>     

      <div class="site-section">
    <div class="container">
      <div class="row">
        <div class="col-md-5 mr-auto">
          <div class="border text-center">
            <?php if ($img != '') { ?>
              <img id="img01" src="../admin/uploads/<?php echo $img; ?> " alt="Image" class="img-fluid p-5"/>
              <script>
                function img001(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      $('#img01').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                  }
                }
              </script>
            <?php } else { echo "no image"; ?>
              <img style="width: 100px;height:auto" id="img01" src="uploads/<?php echo $img; ?> " alt=""/>
              <script>
                function img001(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      $('#img01').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                  }
                }
              </script>
            <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
          <h2 class="text-black"><?php echo $p_name; ?></h2>
          <p><?php echo $p_description; ?></p>
          <p><strong class="text-primary h4">LKR <?php echo $p_price; ?></strong></p>

          <form action="" method="post">
            <input type="hidden" class="form-control" value="<?php echo $p_id; ?>" name="p_id">
            <input type="hidden" class="form-control" value="<?php echo $p_price; ?>" name="p_price">
            <?php if (isset($_SESSION['username'])) { 


                // Check if the product is already in the cart
              error_reporting(error_reporting() & ~E_WARNING);
$already_in_cart = false;
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $userQuery = "SELECT users.id AS user_id, customer_list.id AS c_id 
                FROM users 
                INNER JOIN customer_list 
                ON users.id = customer_list.user_id 
                WHERE username = '$username'";
  $userResult = mysqli_query($connection, $userQuery);
  $userRow = mysqli_fetch_assoc($userResult);
  $customer_id = $userRow['c_id'];

  $cartQuery = "SELECT * FROM cart_list WHERE customer_id = '$customer_id' AND product_id = '$p_id'";
  $cartResult = mysqli_query($connection, $cartQuery);
  if (mysqli_num_rows($cartResult) > 0) {
    $already_in_cart = true;
  }
}



              ?>
              <?php if ($already_in_cart) { ?>
                <p><button type="button" class="buy-now btn btn-sm height-auto px-4 py-3 btn-primary" disabled>Already in Cart</button></p>
              <?php } else { ?>
                <p><button type="submit" class="buy-now btn btn-sm height-auto px-4 py-3 btn-primary" name="submit">Add to Cart</button></p>
              <?php } ?>
            <?php } else { ?>
              <p><button type="button" class="buy-now btn btn-sm height-auto px-4 py-3 btn-primary" disabled>Add to Cart</button></p>
              <p><small class="text-danger">Please log in to add to cart.</small></p>
            <?php } ?>
          </form>
        </div>
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
  </div>
</body>
</html>

<?php
include_once '../database/db.php';

if (isset($_POST['submit']) && !$already_in_cart) {
  $p_id = $_POST['p_id'];
  $p_price = $_POST['p_price'];
  $username = $_SESSION['username'];

  // Retrieve customer_id using username
  $get_customer_id_query = "SELECT users.id AS user_id, customer_list.user_id AS c_user_id, customer_list.id AS c_id FROM users INNER JOIN customer_list ON users.id = customer_list.user_id WHERE username= '$username'";
  $result = mysqli_query($connection, $get_customer_id_query);

  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $customer_id = $row['c_id'];

    // Insert into cart_list
    $query = "INSERT INTO cart_list(customer_id, product_id, quantity, price) VALUES('$customer_id', '$p_id', '1', '$p_price')";

    if (mysqli_query($connection, $query)) {
      echo '<script>
        setTimeout(function() {
          swal({
            title: "SUCCESS...!",
            text: "Product added to cart.",
            type: "success"
          }, function() {
            window.location = "index.php";
          });
        }, 100);
      </script>';
    } else {
      echo "Error: " . mysqli_error($connection);
      echo '<script>
        setTimeout(function() {
          swal({
            title: "ERROR",
            text: "Failed to add product to cart.",
            type: "error"
          }, function() {
            window.location = "index.php";
          });
        }, 100);
      </script>';
    }
  } else {
    echo '<script>
      setTimeout(function() {
        swal({
          title: "ERROR",
          text: "User not found.",
          type: "error"
        }, function() {
          window.location = "index.php";
        });
      }, 100);
    </script>';
  }
}


?>
