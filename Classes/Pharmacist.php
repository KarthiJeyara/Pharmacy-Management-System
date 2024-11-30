<?php
require_once 'UserAccount.php';

class Pharmacist extends UserAccount
{

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

    function deleteFromOrderList($connection, $id)
    {
        $location = "order_list.php";

        $sql = "DELETE FROM order_list WHERE id = $id";
        $stat = $connection->query($sql);

        $this->statMessage($stat, $location);
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

    
}
