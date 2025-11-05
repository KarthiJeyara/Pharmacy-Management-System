<?php
require_once('../Classes/DbConnector.php');

class Product {
    private $connection;

    public function __construct() {
        $this->connection = (new DbConnector())->getConnection();
    }

    public function getAllProducts() {
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

        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "0";
        }
    }

    public function getProductDetails($id) {
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
                FROM product_list INNER JOIN category_list 
                ON product_list.category_id = category_list.id WHERE product_list.id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insertProduct($category, $brand, $name, $description, $dose, $price, $image, $status) {
        $sql = "INSERT INTO product_list (category_id, brand, name, description, dose, price, image_path, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("issssdss", $category, $brand, $name, $description, $dose, $price, $image, $status);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateProduct($id, $category, $brand, $name, $description, $dose, $price, $image, $status) {
        $sql = "UPDATE product_list SET 
                category_id = ?, 
                brand = ?, 
                name = ?, 
                description = ?, 
                dose = ?, 
                price = ?, 
                image_path = ?, 
                status = ? 
                WHERE id = ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("issssdss", $category, $brand, $name, $description, $dose, $price, $image, $status, $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($id) {
        $sql = "DELETE FROM product_list WHERE id = ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getProductCount() {
        $sql = "SELECT COUNT(*) AS row_count FROM product_list";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["row_count"];
        } else {
            return "0";
        }
    }
}
