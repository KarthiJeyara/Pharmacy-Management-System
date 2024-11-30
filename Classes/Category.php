<?php
require_once('../database/db.php');
session_start();

class Category
{
    private $id;
    private $name;
    private $description;
    private $status;
    private $delete_flag;
    private $date_created;
    private $date_updated;


    function __construct($id, $name, $description, $status, $delete_flag, $date_created, $date_updated)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->delete_flag = $delete_flag;
        $this->date_created = $date_created;
        $this->date_updated = $date_updated;
    }

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getStatus()
    {
        return $this->status;
    }

    function getDelete_flag()
    {
        return $this->delete_flag;
    }

    function getDate_created()
    {
        return $this->date_created;
    }

    function getDate_updated()
    {
        return $this->date_updated;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function setDelete_flag($delete_flag)
    {
        $this->delete_flag = $delete_flag;
    }

    function setDate_created($date_created)
    {
        $this->date_created = $date_created;
    }

    function setDate_updated($date_updated)
    {
        $this->date_updated = $date_updated;
    }

    public static function getAll($con)
    {
        $categoryList = [];
        $query = "SELECT * FROM category_list";
        $pstmt = $con->prepare($query);
        $pstmt->execute();
        $resultSet = $pstmt->fetchAll(PDO::FETCH_OBJ);
        if (!empty($resultSet)) {
            foreach ($resultSet as $row) {
                $category = new Category(
                    $row->id,
                    $row->name,
                    $row->description,
                    $row->status,
                    $row->delete_flag,
                    $row->date_created,
                    $row->date_updated

                );
                $categoryList[] = $category;
            }
        }
        return $categoryList;
    }

    public static function getCategoryById($con, $id)
    {
        $query = "SELECT * FROM category_list WHERE id = :id";
        $pstmt = $con->prepare($query);
        $pstmt->bindParam(':id', $id, PDO::PARAM_INT);
        $pstmt->execute();

        // Fetch the single result as an associative array
        $result = $pstmt->fetch(PDO::FETCH_ASSOC);

        // Check if the result is not empty, return the result; otherwise, return null
        if ($result) {
            return $result;
        }
        return null;
    }

    public function addCategory($con)
    {
        try {
            $query = "INSERT INTO category_list(name,description)VALUES(?,?)";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->name);
            $pstmt->bindValue(2, $this->description);


            $pstmt->execute();
            return $pstmt->rowCount() > 0;
        } catch (PDOException $exc) {
            die("Error in User class addCategory: " . $exc->getMessage());
        }
    }

    public static function deleteCategory($con, $id)
    {
        try {
            $query = "DELETE FROM category_list WHERE id =?";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $id);
            $pstmt->execute();
            return $pstmt->rowCount() > 0;
        } catch (PDOException $exc) {
            die("Error in User class deleteCategory: " . $exc->getMessage());
        }
    }


    public function updateCategory($con)
    {
        try {
            $query = "UPDATE category_list SET name = ?, description = ? WHERE id = ?";
            $pstmt = $con->prepare($query);

            // Bind the updated values to the query
            $pstmt->bindValue(1, $this->name);
            $pstmt->bindValue(2, $this->description);

            $pstmt->bindValue(3, $this->id);

            // Execute the query
            $pstmt->execute();

            // Return true if at least one row was updated
            return $pstmt->rowCount() > 0;
        } catch (PDOException $exc) {
            die("Error in Category class updateCategory: " . $exc->getMessage());
        }
    }
}
