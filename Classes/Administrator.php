<?php
require_once 'UserAccount.php';

class Administrator extends UserAccount
{
    function getCategoryCount($connection)
    {
        $sql = "SELECT COUNT(*) AS row_count FROM category_list";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["row_count"];
        } else {
            return "0";
        }
    }

    function getPaidOrderCount($connection)
    {
        $sql = "SELECT COUNT(*) AS row_count FROM order_list WHERE status = 'paid'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["row_count"];
        } else {
            return "0";
        }
    }

    function getOutForDeliveryOrderCount($connection)
    {
        $sql = "SELECT COUNT(*) AS row_count FROM order_list WHERE status = 'out for delivery'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["row_count"];
        } else {
            return "0";
        }
    }

    function getProductCount($connection)
    {
        $sql = "SELECT COUNT(*) AS row_count FROM product_list";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["row_count"];
        } else {
            return "0";
        }
    }

    function getPackedOrderCount($connection)
    {
        $sql = "SELECT COUNT(*) AS row_count FROM order_list WHERE status = 'packed'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["row_count"];
        } else {
            return "0";
        }
    }

    function getCompletedOrderCount($connection)
    {
        $sql = "SELECT COUNT(*) AS row_count FROM order_list WHERE status = 'done'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["row_count"];
        } else {
            return "0";
        }
    }

    function getCategoryList($connection)
    {
        $sql = "SELECT * FROM category_list";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function getCustomerList($connection)
    {
        $sql = "SELECT * FROM customer_list";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function deleteFromCustomerList($connection, $id)
    {
        $location = "customer_list.php";
        $stat = false;
        $sql = "DELETE FROM customer_list WHERE id = $id";
        $result = $connection->query($sql);

        if ($result) {
            $stat = true;
        } else {
            $stat = false;
        }
        $this->statMessage($stat, $location);
    }

    function editCaregory($connection, $pr_id, $name, $description)
    {
        $stat = false;
        $location = "category.php";
        $update = "UPDATE category_list SET name ='$name', description='$description' WHERE id='$pr_id'";

        // Check if the update query was successful
        if (mysqli_query($connection, $update)) {
            // $successStatus = 1;
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
            // $successStatus = 0;
        }

        $this->statMessage($stat, $location);
    }

    function statMessage($stat, $location)
    {
        if ($stat) {
            // Show success message using SweetAlert
            echo '<script>
                setTimeout(function() {
                swal({
                    title: "SUCCESS !",
                    text: "",
                    type: "success"
                }, function() {
                    window.location = "' . $location . '";
                });
                }, 100);
            </script>';
        } else {
            // Show error message using SweetAlert
            echo '<script>
                setTimeout(function() {
                swal({
                    title: "ERROR",
                    text: "",
                    type: "error"
                }, function() {
                    window.location = "' . $location . '";
                });
                }, 100);
            </script>';
        }
    }

    function editCategoryGetExistingDetails($connection, $id)
    {
        $get_edit = "SELECT * FROM category_list WHERE id ='$id'";
        $run_edit = mysqli_query($connection, $get_edit);
        $row_edit = mysqli_fetch_array($run_edit);

        $name = $row_edit['name'];
        $description = $row_edit['description'];

        return array($name, $description);
    }

    function getAllDetailsOfStock($connection)
    {
        $sql = "SELECT 
            product_list.id AS p_id,
            product_list.name AS p_name,
            product_list.description AS p_description,
            product_list.brand AS p_brand,
            product_list.dose AS p_dose,
            product_list.price AS p_price,
            product_list.status AS p_status,
            category_list.name AS c_name
            FROM product_list INNER JOIN category_list 
            ON product_list.category_id = category_list.id";

        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function getProductDetails($connection, $id)
    {
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
                FROM product_list INNER JOIN category_list 
                ON product_list.category_id = category_list.id WHERE product_list.id ='$id'";

        $run_view = mysqli_query($connection, $get_view);
        $row_view = mysqli_fetch_array($run_view);

        $id = $row_view['p_id'];
        $p_name = $row_view['p_name'];
        $p_description = $row_view['p_description'];
        $p_brand = $row_view['p_brand'];
        $p_dose = $row_view['p_dose'];
        $p_price = $row_view['p_price'];
        $p_status = $row_view['p_status'];
        $c_name = $row_view['c_name'];
        $img = $row_view['p_img'];

        return array($id, $p_name, $p_description, $p_brand, $p_dose, $p_price, $p_status, $c_name, $img);
    }

    function getStockDetails($connection, $id)
    {
        error_reporting(error_reporting() & ~E_WARNING);
        $sql = "SELECT 
            stock_list.id AS s_id,
            stock_list.code AS s_code,
            stock_list.quantity AS s_quantity,
            stock_list.expiration AS s_expiration,
            product_list.id AS p_id
            FROM stock_list INNER JOIN product_list 
            ON stock_list.product_id = product_list.id WHERE stock_list.product_id ='$id'";

        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function getInventoryDetailsOfAProduct($connection, $id)
    {
        $get_edit = "SELECT * FROM stock_list WHERE id ='$id'";
        $run_edit = mysqli_query($connection, $get_edit);
        $row_edit = mysqli_fetch_array($run_edit);

        // Assign fetched values to variables
        $code = $row_edit['code'];
        $quantity = $row_edit['quantity'];
        $expiration = $row_edit['expiration'];

        return array($code, $quantity, $expiration);
    }

    function viewInventoryEditAProduct($connection, $pr_id, $code, $quantity, $expiration, $pid)
    {
        $stat = false;
        $location = "view_inventory.php?view_id=$pid";

        $update = "UPDATE stock_list SET code='$code', quantity='$quantity', expiration='$expiration' WHERE id='$pr_id'";
        if (mysqli_query($connection, $update)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
            $stat = false;
        }

        $this->statMessage($stat, $location);
    }

    function getPresctiptionDetails($connection)
    {
        $prescription_sql = "SELECT id, customer_id, code, prescription, price FROM prescription WHERE price = 0";
        $prescription_result = $connection->query($prescription_sql);

        if ($prescription_result->num_rows > 0) {
            return $prescription_result;
        } else {
            return "0";
        }
    }

    function viewOrderDetails($connection, $id)
    {
        $get_order_details = "SELECT 
                                order_list.id AS o_id, 
                                order_list.code AS o_code, 
                                order_list.delivery_address AS o_address,
                                order_list.total_amount AS o_total_amount,
                                order_list.pay_slip AS p_image
                                FROM order_list WHERE order_list.id ='$id'";

        $run_order_details = mysqli_query($connection, $get_order_details);

        if ($run_order_details) {
            $order_details = mysqli_fetch_assoc($run_order_details);
            return $order_details;
        } else {
            echo "Error: " . mysqli_error($connection);
        }


    }

    function editOrderStatusGetExistingData($connection, $id)
    {
        $get_edit = "SELECT 
                        order_list.id AS o_id, 
                        order_list.customer_id AS o_customer_id, 
                        order_list.total_amount AS o_total_amount, 
                        order_list.status AS o_status, 
                        order_list.code AS o_code, 
                        order_list.delivery_address AS o_address
                    FROM order_list 
                    WHERE order_list.id ='$id'";
        $run_edit = mysqli_query($connection, $get_edit);
        $row_edit = mysqli_fetch_array($run_edit);

        $id = $row_edit['o_id'];
        $code = $row_edit['o_code'];
        $total_amount = $row_edit['o_total_amount'];
        $status = $row_edit['o_status'];
        $order_deliver_address = $row_edit['o_address'];

        return array($id, $code, $total_amount, $status, $order_deliver_address);
    }

    function editOrderStatusPerscription($connection, $pr_id, $price, $status)
    {
        $stat = false;
        $location = "order_list_prescription.php";

        $update = "UPDATE order_list SET status='$status', total_amount='$price' WHERE id='$pr_id' ";

        // Execute update query
        if (mysqli_query($connection, $update)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $this->statMessage($stat, $location);
    }

    function deleteOrderPerscription($connection, $id)
    {
        $location = "order_list_prescription.php";

        $sql = "DELETE FROM order_list WHERE id =" . $id;
        $stat = $connection->query($sql);

        if ($stat) {
            $this->statMessage(true, $location);
        } else {
            $this->statMessage(false, $location);
        }

    }

    function orderListViewAllOrders($connection)
    {
        $sql = "SELECT 
                order_list.id AS o_id, 
                order_list.customer_id AS o_customer_id, 
                order_list.total_amount AS o_total_amount, 
                order_list.status AS o_status, 
                order_list.code AS o_code, 
                customer_list.firstname AS c_firstname, 
                customer_list.middlename AS c_middlename 
            FROM 
                order_list INNER JOIN customer_list 
            ON 
                order_list.customer_id = customer_list.id WHERE order_list.pay_slip = '-'";

        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function deleteFromOrderList($connection, $id)
    {
        $location = "order_list.php";

        $sql = "DELETE FROM order_list WHERE id = $id";
        $stat = $connection->query($sql);

        $this->statMessage($stat, $location);
    }

    function viewOrderGetOrderDetails($connection, $id)
    {
        $get_order_details = "SELECT 
                            order_list.id AS o_id, 
                            order_list.code AS o_code, 
                            order_list.delivery_address AS o_address,
                            order_list.total_amount AS o_total_amount, 
                            GROUP_CONCAT(product_list.name SEPARATOR ', ') AS product_names,
                            GROUP_CONCAT(product_list.dose SEPARATOR ', ') AS product_doses,
                            GROUP_CONCAT(order_items.quantity SEPARATOR ', ') AS item_quantities
                        FROM order_list 
                        INNER JOIN order_items ON order_list.id = order_items.order_id 
                        INNER JOIN product_list ON product_list.id = order_items.product_id 
                        WHERE order_list.id ='$id'
                        GROUP BY order_list.id";

        $run_order_details = mysqli_query($connection, $get_order_details);

        if ($run_order_details) {
            $order_details = mysqli_fetch_assoc($run_order_details);
            return $order_details;
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }

    function editOrderStatusGetExistingDetails($connection, $id)
    {
        $get_edit = "SELECT 
                        order_list.id AS o_id, 
                        order_list.customer_id AS o_customer_id, 
                        order_list.total_amount AS o_total_amount, 
                        order_list.status AS o_status, 
                        order_list.code AS o_code, 
                        order_list.delivery_address AS o_address,
                        order_items.product_id AS o_pro_id,
                        product_list.name AS p_pro_name 
                    FROM order_list 
                    INNER JOIN order_items 
                    ON order_list.id = order_items.order_id 
                    INNER JOIN product_list 
                    ON product_list.id = order_items.product_id 
                    WHERE order_list.id ='$id'";
        $run_edit = mysqli_query($connection, $get_edit);
        $row_edit = mysqli_fetch_array($run_edit);

        // Assign fetched values to variables
        $id = $row_edit['o_id'];
        $code = $row_edit['o_code'];
        $total_amount = $row_edit['o_total_amount'];
        $status = $row_edit['o_status'];
        $order_product_id = $row_edit['o_pro_id'];
        $product_name = $row_edit['p_pro_name'];
        $order_deliver_address = $row_edit['o_address'];

        return array($id, $code, $total_amount, $status, $order_product_id, $product_name, $order_deliver_address);
    }

    function orderListUpdateStatus($connection, $status, $pr_id)
    {
        $stat = false;
        $location = "order_list.php";

        $update = "UPDATE order_list SET status='$status' WHERE id='$pr_id' ";

        // Execute update query
        if (mysqli_query($connection, $update)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $this->statMessage($stat, $location);
    }

    function productListGetDetails($connection)
    {
        $sql = "SELECT 
                product_list.id AS p_id,
                product_list.name AS p_name,
                product_list.description AS p_description,
                product_list.brand AS p_brand,
                product_list.dose AS p_dose,
                product_list.price AS p_price,
                product_list.status AS p_status,
                category_list.name AS c_name
                FROM product_list INNER JOIN category_list 
                ON product_list.category_id = category_list.id";

        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function productListInsertANewProduct($connection, $category, $brand, $name, $description, $dose, $price, $image, $status)
    {
        $stat = false;
        $location = "product_list.php";

        $query = "INSERT INTO product_list(category_id, brand, name, description, dose, price, image_path, status) VALUES ('$category', '$brand', '$name', '$description', '$dose', '$price', '$image', '$status')";

        if (mysqli_query($connection, $query)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $this->statMessage($stat, $location);
    }

    function viewAProduct($connection, $id)
    {
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
                FROM product_list INNER JOIN category_list 
                ON product_list.category_id = category_list.id WHERE product_list.id ='$id'";
        $run_view = mysqli_query($connection, $get_view);
        $row_view = mysqli_fetch_array($run_view);

        $id = $row_view['p_id'];
        $p_name = $row_view['p_name'];
        $p_description = $row_view['p_description'];
        $p_brand = $row_view['p_brand'];
        $p_dose = $row_view['p_dose'];
        $p_price = $row_view['p_price'];
        $p_status = $row_view['p_status'];
        $c_name = $row_view['c_name'];
        $img = $row_view['p_img'];

        return array($id, $p_name, $p_description, $p_brand, $p_dose, $p_price, $p_status, $c_name, $img);
    }

    function productListDeleteProduct($connection, $id)
    {
        $sql = "DELETE FROM product_list WHERE id =" . $id;
        $stat = $connection->query($sql);

        $location = "product_list.php";

        if ($stat) {
            $this->statMessage(true, $location);
        } else {
            $this->statMessage(false, $location);
        }
    }

    function editProductGetExistingProductDetails($connection, $id)
    {
        $get_edit = "SELECT 
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
                FROM product_list INNER JOIN category_list 
                ON product_list.category_id = category_list.id WHERE product_list.id ='$id'";
        $run_edit = mysqli_query($connection, $get_edit); // Execute the query
        $row_edit = mysqli_fetch_array($run_edit); // Fetch the product details

        $id = $row_edit['p_id'];
        $p_name = $row_edit['p_name'];
        $p_description = $row_edit['p_description'];
        $p_brand = $row_edit['p_brand'];
        $p_dose = $row_edit['p_dose'];
        $p_price = $row_edit['p_price'];
        $p_status = $row_edit['p_status'];
        $c_name = $row_edit['c_name'];
        $c_id = $row_edit['c_id'];
        $img = $row_edit['p_img'];

        return array($id, $p_name, $p_description, $p_brand, $p_dose, $p_price, $p_status, $c_name, $c_id, $img);
    }

    function editProductUpdateProductDetails($connection, $update)
    {
        $stat = false;
        if (mysqli_query($connection, $update)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $location = "product_list.php";

        $this->statMessage($stat, $location);
    }

    function getAllEmployee($connection)
    {
        $sql = "SELECT * FROM users WHERE type != 'customer'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function addEmployee($connection, $f_name, $m_name, $l_name, $u_name, $hashed_password, $avatar, $type)
    {
        $query = "INSERT INTO users(firstname, middlename, lastname, username, password, avatar, type) 
                VALUES('$f_name','$m_name','$l_name','$u_name','$hashed_password','$avatar','$type')";

        $stat = false;
        if (mysqli_query($connection, $query)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $location = "employee.php";

        $this->statMessage($stat, $location);
    }

    function deleteEmployee($connection, $id)
    {
        $sql = "DELETE FROM users WHERE id =" . $id;
        $stat = $connection->query($sql);

        $location = "employee.php";

        if ($stat) {
            $this->statMessage(true, $location);
        } else {
            $this->statMessage(false, $location);
        }
    }

    function editEmployee($connection, $pr_id, $f_name, $m_name, $l_name, $type)
    {
        $stat = false;
        $location = "employee.php";
        $update = "UPDATE users SET firstname ='$f_name', middlename='$m_name', lastname = '$l_name', type = '$type' WHERE id='$pr_id' ";

        // Check if the update query was successful
        if (mysqli_query($connection, $update)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $this->statMessage($stat, $location);
    }

    function viewInventoryAddAProduct($connection, $code, $quantity, $expiration, $product_id)
    {
        $quary = "INSERT INTO stock_list(product_id,code,quantity,expiration) VALUES ('$product_id','$code','$quantity','$expiration')";
        $stat = false;
        $location = "view_inventory.php?view_id=$product_id";

        if (mysqli_query($connection, $quary)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $this->statMessage($stat, $location);
    }

    function deleteStock($connection, $stock_id)
    {
        $location = "view_inventory.php";
        $sql = "DELETE FROM stock_list WHERE id = $stock_id";
        $stat = $connection->query($sql);

        if ($stat) {
            $this->statMessage(true, $location);
        } else {
            $this->statMessage(false, $location);
        }
    }

    function addCategory($connection, $name, $description)
    {
        $query = "INSERT INTO category_list(name,description) 
                VALUES('$name','$description')";
        $stat = false;
        if (mysqli_query($connection, $query)) {
            $stat = true;
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        $location = "category.php";

        $this->statMessage($stat, $location);
    }

    function deleteCategory($connection, $id)
    {
        $sql = "DELETE FROM category_list WHERE id =" . $id;
        $stat = $connection->query($sql);

        $location = "category.php";

        if ($stat) {
            $this->statMessage(true, $location);
        } else {
            $this->statMessage(false, $location);
        }
    }

    function employeeEditGetExistingDetails($connection, $id)
    {
        $get_edit = "SELECT * FROM users WHERE id ='$id'";
        $run_edit = mysqli_query($connection, $get_edit);
        $row_edit = mysqli_fetch_array($run_edit);

        $id = $row_edit['id'];
        $firstname = $row_edit['firstname'];
        $middlename = $row_edit['middlename'];
        $lastname = $row_edit['lastname'];
        $type = $row_edit['type'];

        return array($id, $firstname, $middlename, $lastname, $type);
    }

    function getOrderListPrescriptions($connection)
    {
        $sql = "SELECT 
                order_list.id AS o_id, 
                order_list.customer_id AS o_customer_id, 
                order_list.total_amount AS o_total_amount, 
                order_list.status AS o_status, 
                order_list.code AS o_code, 
                customer_list.firstname AS c_firstname, 
                customer_list.middlename AS c_middlename 
            FROM 
                order_list INNER JOIN customer_list 
            ON 
                order_list.customer_id = customer_list.id WHERE order_list.pay_slip != '-'";

        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    function getOrderListPrescriptionPriceUpdate($connection, $id, $newPrice)
    {
        $updateSuccess = false;
        if ($newPrice >= 0) {
            $sql1 = "UPDATE prescription SET price = '$newPrice' WHERE id = '$id'";

            if ($connection->query($sql1) === TRUE) {
                $updateSuccess = true; // Set success flag
            }
        }

        if ($updateSuccess) {
            echo '<script>alert("Price updated successfully."); window.location.href = "order_list_prescription.php";</script>';
        } elseif (isset($_POST['update_price'])) {
            echo '<script>alert("Error updating price.");</script>';
        }
    }

    function viewCustomergetDetails($connection, $id)
    {
        $get_view = "SELECT * FROM customer_list WHERE id=$id";
        $run_view = mysqli_query($connection, $get_view);
        $row_view = mysqli_fetch_array($run_view);

        $id = $row_view['id'];
        $firstname = $row_view['firstname'];
        $middlename = $row_view['middlename'];
        $lastname = $row_view['lastname'];
        $contact = $row_view['contact'];
        $email = $row_view['email'];

        return array($id, $firstname, $middlename, $lastname, $contact, $email);
    }
}