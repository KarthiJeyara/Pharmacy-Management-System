<?php
session_start();

class UserAccount
{
    private $accountID;
    private $username;
    private $password;
    private $role;

    function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    function login($connection)
    {
        error_reporting(error_reporting() & ~E_WARNING);
        $query1 = "SELECT * FROM users WHERE username = '{$this->username}'";
        $result1 = mysqli_query($connection, $query1);

        if ($result1) {
            $row = mysqli_fetch_array($result1);
            if ($row) {
                $dbpassword = $row['password'];
                $dbName = $row['username'];
                $dbtype = $row['type'];

                // Verify the password
                if (password_verify($this->password, $dbpassword) && $this->username == $dbName) {
                    $_SESSION["username"] = $dbName;
                    $_SESSION["type"] = $dbtype;
                    if ($dbtype == 'admin') {
                        echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "WELCOME ...!!!",
                        text: "Login is Successfully.",
                        type: "success"
                        }, function() {
                        window.location = "../admin/admin_dashboard.php";
                        });
                        },100);
                        </script>';
                    } elseif ($dbtype == 'customer') {
                        echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "WELCOME ...!!!",
                        text: "Login is Successfully.",
                        type: "success"
                        }, function() {
                        window.location = "../customer/index.php";
                        });
                        },100);
                        </script>';
                    } elseif ($dbtype == 'pharmacist') {
                        echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "WELCOME ...!!!",
                        text: "Login is Successfully.",
                        type: "success"
                        }, function() {
                        window.location = "../pharmacist/pharmacist_dashboard.php";
                        });
                        },100);
                        </script>';
                    } elseif ($dbtype == 'cashier') {
                        echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "WELCOME ...!!!",
                        text: "Login is Successfully.",
                        type: "success"
                        }, function() {
                        window.location = "../cashier/bill.php";
                        });
                        },100);
                        </script>';
                    }
                } else {
                    echo '<script>
                    setTimeout(function() {
                    swal({
                    title: "ERROR....",
                    text: "Wrong Credential.",
                    type: "error"
                    }, function() {
                    window.location = "index.php";
                    });
                    },100);
                    </script>';
                }
            } else {
                echo '<script>
                setTimeout(function() {
                swal({
                title: "ERROR....",
                text: "Wrong Credential.",
                type: "error"
                }, function() {
                window.location = "index.php";
                });
                },100);
                </script>';
            }
        }
    }

    function logout()
    {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 86400, '/');
        }


        session_destroy();
        header('location:../login/index.php');
    }
}