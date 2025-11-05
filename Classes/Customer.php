<?php
require_once 'UserAccount.php';

class Customer extends UserAccount
{
    private $connection;
    private $customer_id;
    private $username;

    // Constructor to initialize database connection and customer details
    public function __construct($dbConnection, $username)
    {
        $this->connection = $dbConnection;
        $this->username = $username;
        $this->setCustomerId();
    }

    // Function to set customer_id based on username
    private function setCustomerId()
    {
        $query = "SELECT users.id AS user_id, customer_list.id AS c_id 
                  FROM users 
                  INNER JOIN customer_list 
                  ON users.id = customer_list.user_id 
                  WHERE username = '$this->username'";
        $result = mysqli_query($this->connection, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->customer_id = $row['c_id'];
        } else {
            $this->customer_id = null;
        }
    }

    // Method to view products based on parameters like category, price range, or search keyword
    public function viewProducts($category = null, $minPrice = 0, $maxPrice = PHP_INT_MAX, $searchKeyword = '')
    {
        $query = "SELECT product_list.id AS p_id, product_list.name AS p_name, product_list.description AS p_description,
                         product_list.brand AS p_brand, product_list.dose AS p_dose, product_list.price AS p_price,
                         product_list.status AS p_status, product_list.image_path AS p_img, category_list.name AS c_name
                  FROM product_list 
                  INNER JOIN category_list ON product_list.category_id = category_list.id
                  WHERE product_list.price BETWEEN $minPrice AND $maxPrice";

        // Apply category filter if provided
        if ($category) {
            $query .= " AND category_list.name = '$category'";
        }

        // Apply search keyword filter if provided
        if ($searchKeyword) {
            $query .= " AND (product_list.name LIKE '%$searchKeyword%' OR product_list.description LIKE '%$searchKeyword%')";
        }

        $result = mysqli_query($this->connection, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
            return $products;
        } else {
            return "No products found.";
        }
    }




    // Add a product to the cart
    public function addToCart($productId, $quantity = 1) {
        if ($this->isProductInCart($productId)) {
            return "Product already in cart.";
        }

        $query = "INSERT INTO cart_list (customer_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('iii', $this->customerId, $productId, $quantity);
        if ($stmt->execute()) {
            $stmt->close();
            return "Product added to cart.";
        } else {
            $stmt->close();
            return "Failed to add product to cart.";
        }
    }

    // Check if a product is already in the cart
    private function isProductInCart($productId) {
        $query = "SELECT * FROM cart_list WHERE customer_id = ? AND product_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('ii', $this->customerId, $productId);
        $stmt->execute();
        $stmt->store_result();
        $isInCart = $stmt->num_rows > 0;
        $stmt->close();
        return $isInCart;
    }

    // Update the quantity of a product in the cart
    public function updateCartQuantity($productId, $newQuantity) {
        $query = "UPDATE cart_list SET quantity = ? WHERE customer_id = ? AND product_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('iii', $newQuantity, $this->customerId, $productId);
        if ($stmt->execute()) {
            $stmt->close();
            return "Quantity updated.";
        } else {
            $stmt->close();
            return "Failed to update quantity.";
        }
    }

    // Remove a product from the cart
    public function removeFromCart($productId) {
        $query = "DELETE FROM cart_list WHERE customer_id = ? AND product_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('ii', $this->customerId, $productId);
        if ($stmt->execute()) {
            $stmt->close();
            return "Product removed from cart.";
        } else {
            $stmt->close();
            return "Failed to remove product from cart.";
        }
    }

    // Get all products in the cart
    public function getCartItems() {
        $query = "SELECT product_list.name AS product_name, 
                         product_list.price AS product_price, 
                         cart_list.quantity AS quantity 
                  FROM cart_list 
                  INNER JOIN product_list ON cart_list.product_id = product_list.id 
                  WHERE cart_list.customer_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $this->customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
        $stmt->close();
        return $cartItems;
    }

    


 public function uploadPrescription($username, $c_order_notes, $file)
    {
        $customer_id = $this->getCustomerId($username);
        if (!$customer_id) {
            return [
                'success' => false,
                'message' => 'Failed to fetch user details.'
            ];
        }

        // Sanitize the order notes
        $c_order_notes = $this->connection->real_escape_string($c_order_notes);

        // Handle file upload and insert into database
        return $this->handleFileUpload($customer_id, $c_order_notes, $file);
    }

   

    private function handleFileUpload($customer_id, $c_order_notes, $file)
    {
        $prescription_slip = $this->connection->real_escape_string($file["name"]);
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($prescription_slip);

        if ($this->moveUploadedFile($file, $targetFile)) {
            return $this->insertPrescription($customer_id, $prescription_slip, $c_order_notes);
        } else {
            return [
                'success' => false,
                'message' => 'Failed to upload file.'
            ];
        }
    }

    private function moveUploadedFile($file, $targetFile)
    {
        return move_uploaded_file($file["tmp_name"], $targetFile);
    }

    private function insertPrescription($customer_id, $prescription_slip, $c_order_notes)
    {
        $query_order = "INSERT INTO prescription (customer_id, prescription, price, order_notes) 
                        VALUES (?, ?, 0, ?)";
        
        if ($stmt === $this->connection->prepare($query_order)) {
            $stmt->bind_param("iss", $customer_id, $prescription_slip, $c_order_notes);
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Prescription uploaded successfully.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to place order. SQL Error: ' . $stmt->error
                ];
            }
        }
        return [
            'success' => false,
            'message' => 'Failed to prepare SQL statement.'
        ];
    }


 public function viewCartPrescription($username)
    {
        $customer_id = $this->getCustomerId($username);
        if (!$customer_id) {
            return [
                'success' => false,
                'message' => 'Failed to fetch user details.'
            ];
        }

        return $this->getCartPrescriptions($customer_id);
    }

   

    private function getCartPrescriptions($customer_id)
    {
        $query = "SELECT prescription.id, prescription.prescription, prescription.order_notes 
                  FROM prescription 
                  WHERE customer_id = ? AND status = 'in_cart'"; // Assuming there's a status to indicate it's in the cart

        if ($stmt = $this->connection->prepare($query)) {
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $prescriptions = $result->fetch_all(MYSQLI_ASSOC);
                return [
                    'success' => true,
                    'prescriptions' => $prescriptions
                ];
            } else {
                return [
                    'success' => true,
                    'prescriptions' => [] // Return empty array if no prescriptions in the cart
                ];
            }
        }
        return [
            'success' => false,
            'message' => 'Failed to prepare SQL statement.'
        ];
    }


public function viewOrderItems($orderId)
    {
        return $this->getOrderDetails($orderId);
    }

    private function getOrderDetails($orderId)
    {
        // Fetch order details
        $orderQuery = "SELECT * FROM order_list WHERE id = ?";
        
        if ($stmt === $this->connection->prepare($orderQuery)) {
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $orderResult = $stmt->get_result();

            if ($orderResult && $orderResult->num_rows > 0) {
                $orderData = $orderResult->fetch_assoc();

                // Fetch order items based on order ID
                $itemsQuery = "SELECT * FROM order_items WHERE order_id = ?";
                if ($itemsStmt === $this->connection->prepare($itemsQuery)) {
                    $itemsStmt->bind_param("i", $orderId);
                    $itemsStmt->execute();
                    $itemsResult = $itemsStmt->get_result();
                    
                    $items = [];
                    while ($item = $itemsResult->fetch_assoc()) {
                        $items[] = $item;
                    }

                    return [
                        'success' => true,
                        'order' => $orderData,
                        'items' => $items
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Order not found.'
                ];
            }
        }
        return [
            'success' => false,
            'message' => 'Failed to prepare SQL statement.'
        ];
    }



public function cancelOrder($orderId)
    {
        // Begin transaction
        $this->connection->begin_transaction();

        try {
            // Delete order items first
            $deleteItemsQuery = "DELETE FROM order_items WHERE order_id = ?";
            if ($stmt === $this->connection->prepare($deleteItemsQuery)) {
                $stmt->bind_param("i", $orderId);
                $stmt->execute();
                
                // Check if items were deleted
                if ($stmt->affected_rows === 0) {
                    throw new Exception("No order items found for this order.");
                }
            } else {
                throw new Exception("Failed to prepare statement for deleting order items.");
            }

            // Delete the order from order_list
            $deleteOrderQuery = "DELETE FROM order_list WHERE id = ?";
            if ($stmt = $this->connection->prepare($deleteOrderQuery)) {
                $stmt->bind_param("i", $orderId);
                $stmt->execute();

                // Check if order was deleted
                if ($stmt->affected_rows === 0) {
                    throw new Exception("Order not found or already canceled.");
                }
            } else {
                throw new Exception("Failed to prepare statement for deleting the order.");
            }

            // Commit transaction
            $this->connection->commit();
            return [
                'success' => true,
                'message' => 'Order canceled successfully.'
            ];

        } catch (Exception $e) {
            // Rollback transaction in case of error
            $this->connection->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

 public function removeOrder($orderId)
    {
        // Begin transaction
        $this->connection->begin_transaction();

        try {
            // Delete order items first
            $deleteItemsQuery = "DELETE FROM order_items WHERE order_id = ?";
            if ($stmt = $this->connection->prepare($deleteItemsQuery)) {
                $stmt->bind_param("i", $orderId);
                $stmt->execute();

                // Check if items were deleted
                if ($stmt->affected_rows === 0) {
                    throw new Exception("No order items found for this order.");
                }
            } else {
                throw new Exception("Failed to prepare statement for deleting order items.");
            }

            // Delete the order from order_list
            $deleteOrderQuery = "DELETE FROM order_list WHERE id = ?";
            if ($stmt === $this->connection->prepare($deleteOrderQuery)) {
                $stmt->bind_param("i", $orderId);
                $stmt->execute();

                // Check if order was deleted
                if ($stmt->affected_rows === 0) {
                    throw new Exception("Order not found or already removed.");
                }
            } else {
                throw new Exception("Failed to prepare statement for deleting the order.");
            }

            // Commit transaction
            $this->connection->commit();
            return [
                'success' => true,
                'message' => 'Order removed successfully.'
            ];

        } catch (Exception $e) {
            // Rollback transaction in case of error
            $this->connection->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }



 public function searchProducts($searchTerm, $categoryId = null)
    {
        $searchTerm = '%' . $this->connection->real_escape_string($searchTerm) . '%'; // Prepare search term for LIKE query

        // Build the SQL query
        $sql = "SELECT 
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
                INNER JOIN category_list ON product_list.category_id = category_list.id 
                WHERE product_list.name LIKE ?";

        // If category ID is provided, add to the query
        if ($categoryId !== null) {
            $sql .= " AND product_list.category_id = ?";
        }

        $stmt = $this->connection->prepare($sql);

        if ($categoryId !== null) {
            $stmt->bind_param("si", $searchTerm, $categoryId);
        } else {
            $stmt->bind_param("s", $searchTerm);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }


 public function viewCartItems()
    {
        $sql = "SELECT 
                    cart_list.id AS cart_id,
                    product_list.id AS product_id,
                    product_list.name AS product_name,
                    product_list.price AS product_price,
                    cart_list.quantity AS quantity,
                    (product_list.price * cart_list.quantity) AS total_price
                FROM cart_list
                INNER JOIN product_list ON cart_list.product_id = product_list.id
                WHERE cart_list.customer_id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $this->customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }

        return $cartItems;
    }

    public function displayCart()
    {
        $cartItems = $this->viewCartItems();

        if (empty($cartItems)) {
            echo "<p>Your cart is empty.</p>";
        } else {
            echo "<table class='table'>";
            echo "<thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price (LKR)</th>
                        <th>Quantity</th>
                        <th>Total Price (LKR)</th>
                    </tr>
                  </thead>
                  <tbody>";

            foreach ($cartItems as $item) {
                echo "<tr>
                        <td>{$item['product_name']}</td>
                        <td>{$item['product_price']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$item['total_price']}</td>
                      </tr>";
            }

            echo "</tbody>
                  </table>";
        }
    }

public function updateOrderQuantity($productId, $newQuantity)
    {
        // Ensure the quantity is a positive integer
        if ($newQuantity <= 0) {
            throw new InvalidArgumentException("Quantity must be greater than zero.");
        }

        // Prepare the SQL statement to update the cart item
        $sql = "UPDATE cart_list 
                SET quantity = ?, price = (SELECT price FROM product_list WHERE id = ?) * ? 
                WHERE customer_id = ? AND product_id = ?";

        // Prepare and execute the statement
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("iiisi", $newQuantity, $productId, $newQuantity, $this->customerId, $productId);

        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }
    }

}


<?php require_once('../database/db.php'); 