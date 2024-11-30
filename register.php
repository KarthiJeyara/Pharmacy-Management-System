<!DOCTYPE html>
<html lang="en">

<head>
    <title>Phrime Care | Register</title>
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
</head>

<body>
    <div class="site-wrap">
        <?php include 'menu.php'; ?>
        <div class="site-navbar py-2">
            <div class="site-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mb-5 mb-md-0 mx-auto">
                            <h2 class="h3 mb-3" style="text-align:center;color:#51eaea">Prime Care | Customer Registration</h2>
                            <div class="p-10 p-lg-5 border">
                                <form action="" method="post" id="registrationForm" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="text-black">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-black">Middle Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="middle_name" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="text-black">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-black">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="text-black">Gender</label>
                                            <select class="form-control" name="gender">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-black">Avatar <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="image" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="text-black">Email </label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="text-black">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" id="password" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-black">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="confirm_password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-black"><input type="checkbox" required> I accept the Terms of Use & Privacy Policy</label>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" name="submit">Register</button>
                                        </div>
                                    </div> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var password = document.getElementById("password");
            var confirm_password = document.getElementById("confirm_password");

            function validatePassword() {
                if (password.value != confirm_password.value) {
                    confirm_password.setCustomValidity("Passwords do not match");
                } else {
                    confirm_password.setCustomValidity('');
                }
            }

            password.onchange = validatePassword;
            confirm_password.onkeyup = validatePassword;
        });
    </script>

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

<!-- Insert data to database -->
<?php

class UserRegistration {
    private $connection;

    public function __construct($db) {
        $this->connection = $db;
    }

    public function register($data, $file) {
        $first_name = $data['first_name'];
        $middle_name = $data['middle_name'];
        $last_name = $data['last_name'];
        $contact = $data['contact'];
        $email = $data['email'];
        $password = $data['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $gender = $data['gender'];
        $image = $file["image"]["name"];
        move_uploaded_file($file["image"]["tmp_name"], "uploads/" . $image);

        if ($this->emailExists($email)) {
            $this->showEmailExistsMessage();
            return;
        }

        $this->connection->begin_transaction();

        try {
            $user_id = $this->insertUser($first_name, $middle_name, $last_name, $email, $hashed_password, $image);
            $this->insertCustomer($user_id, $first_name, $middle_name, $last_name, $gender, $contact, $email, $hashed_password, $image);
            $this->connection->commit();

            $this->showSuccessMessage();

        } catch (Exception $e) {
            $this->connection->rollback();
            $this->showErrorMessage();
        }
    }

    private function emailExists($email) {
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    private function insertUser($first_name, $middle_name, $last_name, $email, $hashed_password, $image) {
        $query = "INSERT INTO users (firstname, middlename, lastname, username, password, avatar, type) 
                  VALUES (?, ?, ?, ?, ?, ?, 'customer')";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('ssssss', $first_name, $middle_name, $last_name, $email, $hashed_password, $image);
        if (!$stmt->execute()) {
            throw new Exception("User insertion failed: " . $stmt->error);
        }
        return $stmt->insert_id;
    }

    private function insertCustomer($user_id, $first_name, $middle_name, $last_name, $gender, $contact, $email, $hashed_password, $image) {
        $query = "INSERT INTO customer_list (user_id, firstname, middlename, lastname, gender, contact, email, password, avatar) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('issssssss', $user_id, $first_name, $middle_name, $last_name, $gender, $contact, $email, $hashed_password, $image);
        if (!$stmt->execute()) {
            throw new Exception("Customer insertion failed: " . $stmt->error);
        }
    }

    private function showEmailExistsMessage() {
        echo '<script>
                setTimeout(function() {
                  swal({
                    title: "ERROR....",
                    text: "This email is already taken.",
                    type: "error"
                  }, function() {
                    window.location = "register.php";
                  });
                }, 100);
              </script>';
    }

    private function showSuccessMessage() {
        echo '<script>
                setTimeout(function() {
                  swal({
                    title: "WELCOME ...!!!",
                    text: "Registration is Successful.",
                    type: "success"
                  }, function() {
                    window.location = "index.php";
                  });
                }, 100);
              </script>';
    }

    private function showErrorMessage() {
        echo '<script>
                setTimeout(function() {
                  swal({
                    title: "ERROR....",
                    text: "Registration failed. Please try again.",
                    type: "error"
                  }, function() {
                    window.location = "register.php";
                  });
                }, 100);
              </script>';
    }
}

if (isset($_POST['submit'])) {
    $db = new mysqli("localhost","root","","pharmacy");
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $registration = new UserRegistration($db);
    $registration->register($_POST, $_FILES);
    $db->close();
}
?>
<!-- End inserting data to database -->
