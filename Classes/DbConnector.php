<?php

class DbConnector
{
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "pharmacy";

    public function getConnection()
    {
        $connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        if (!$connection) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        return $connection;
    }
}