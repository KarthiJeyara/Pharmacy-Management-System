<?php
require_once 'UserAccount.php';
require_once('../database/db.php');

class Cashier extends UserAccount{
    private $db;
    private $username;

    public function __construct($db, $username) {
        $this->db = $db;
        $this->username = $username;
    }

   

    public function generateBill($customerName, $medicines) {
        // Validate input
        if (empty($customerName) || empty($medicines)) {
            return false;
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($medicines as $medicine) {
            $subtotal += $medicine['price'];
        }

        // Here you could add code to save the bill to the database
        // For example:
        // $this->saveBillToDatabase($customerName, $medicines, $subtotal);

        return [
            'customer_name' => $customerName,
            'medicines' => $medicines,
            'subtotal' => $subtotal,
            'cashier' => $this->username
        ];
    }

    // This method would be used to save the bill to the database
    private function saveBillToDatabase($customerName, $medicines, $subtotal) {
        // Implementation depends on your database structure
        // This is just a placeholder
        $stmt = $this->db->prepare("INSERT INTO bills (customer_name, subtotal, cashier) VALUES (?, ?, ?)");
        $stmt->execute([$customerName, $subtotal, $this->username]);
        $billId = $this->db->lastInsertId();

        foreach ($medicines as $medicine) {
            $stmt = $this->db->prepare("INSERT INTO bill_items (bill_id, medicine_name, price) VALUES (?, ?, ?)");
            $stmt->execute([$billId, $medicine['name'], $medicine['price']]);
        }
    }
}