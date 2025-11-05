<?php
require_once('../database/db.php');
session_start();

class User {
    private $connection;
    private $username;

    public function __construct($connection, $username) {
        $this->connection = $connection;
        $this->username = $username;
    }

    public function getCustomerId() {
        $query = "SELECT customer_list.id AS c_id 
                  FROM users 
                  INNER JOIN customer_list ON users.id = customer_list.user_id 
                  WHERE username= '{$this->username}'";
        $result = mysqli_query($this->connection, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['c_id'];
    }
}

class Cart {
    private $connection;
    private $customerId;

    public function __construct($connection, $customerId) {
        $this->connection = $connection;
        $this->customerId = $customerId;
    }

    public function updateQuantity($productId, $quantity) {
        $query = "UPDATE cart_list SET quantity = '$quantity' 
                  WHERE product_id = '$productId' AND customer_id = '$this->customerId'";
        mysqli_query($this->connection, $query);
    }

    public function deleteProduct($productId) {
        $query = "DELETE FROM cart_list 
                  WHERE product_id = '$productId' AND customer_id = '$this->customerId'";
        mysqli_query($this->connection, $query);
    }

    public function getCartProducts() {
        $query = "SELECT product_list.id AS p_id, 
                         product_list.name AS p_name, 
                         product_list.price AS p_price, 
                         product_list.image_path AS p_image_path, 
                         cart_list.quantity AS c_quantity 
                  FROM product_list 
                  INNER JOIN cart_list ON product_list.id = cart_list.product_id 
                  WHERE cart_list.customer_id = '{$this->customerId}'";
        return $this->connection->query($query);
    }
}

class Product {
    public $id;
    public $name;
    public $price;
    public $imagePath;
    public $quantity;

    public function __construct($id, $name, $price, $imagePath, $quantity) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->imagePath = $imagePath;
        $this->quantity = $quantity;
    }

    public function totalPrice() {
        return $this->price * $this->quantity;
    }
}

if (!isset($_SESSION["username"])) {
    header('location:index.php');
    exit();
}

$username = $_SESSION['username'];
$user = new User($connection, $username);
$customerId = $user->getCustomerId();
$cart = new Cart($connection, $customerId);

if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    $cart->updateQuantity($product_id, $new_quantity);
    header("Location: cart.php");
    exit();
}

if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    $cart->deleteProduct($product_id);
    header("Location: cart.php");
    exit();
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
                    <form class="col-md-12" method="post" action="cart.php">
                        <div class="site-blocks-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="product-thumbnail">Image</th>
                                        <th class="product-name">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-total">Total</th>
                                        <th class="product-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $cart->getCartProducts();
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $product = new Product($row['p_id'], $row['p_name'], $row['p_price'], $row['p_image_path'], $row['c_quantity']);
                                    ?>
                                            <tr>
                                                <td><?= $product->id; ?></td>
                                                <td class="product-thumbnail">
                                                    <img src="../admin/uploads/<?= $product->imagePath; ?>" alt="Image" class="img-fluid">
                                                </td>
                                                <td class="product-name">
                                                    <h2 class="h5 text-black"><?= $product->name; ?></h2>
                                                </td>
                                                <td class="product-price" id="price-<?= $product->id; ?>"><?= $product->price; ?></td>
                                                <td class="product-quantity">
                                                    <form method="post" action="cart.php">
                                                        <input type="number" class="form-control quantity-input" name="quantity" value="<?= $product->quantity; ?>" min="1" max="10" width="10px" onchange="this.form.submit()">
                                                        <input type="hidden" name="product_id" value="<?= $product->id; ?>">
                                                        <input type="hidden" name="update_quantity" value="1">
                                                    </form>
                                                </td>
                                                <td class="product-total" id="total-<?= $product->id; ?>"><?= $product->totalPrice(); ?></td>
                                                <td class="product-remove">
                                                    <form method="post" action="cart.php">
                                                        <input type="hidden" name="product_id" value="<?= $product->id; ?>">
                                                        <button type="submit" name="delete" class="btn btn-primary height-auto btn-sm">X</button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php
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

                <?php if ($result->num_rows > 0) { ?>
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
                                        <div class="col-md-12 text-right border-bottom mb-5">
                                            <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <span class="text-black">Subtotal</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <strong id="subtotal" class="text-black">LKR 0.00</strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary btn-lg btn-block" onclick="window.location='online_payment.php'">Proceed To Checkout</button>
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