<?php
// models/Admin.php
require_once 'config/database.php';

class Admin {
    private $conn;
    private $table_name = "Admin";

    public $id_admin;
    public $username;
    public $password;
    public $nama_lengkap;
    public $role;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Find admin by username
    public function findByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new admin
    public function create($username, $password, $nama_lengkap, $role = 'operator') {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, password, nama_lengkap, role) 
                  VALUES (:username, :password, :nama_lengkap, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':nama_lengkap', $nama_lengkap);
        $stmt->bindParam(':role', $role);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get all admins
    public function readAll() {
        $query = "SELECT id_admin, username, nama_lengkap, role, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Count total admins
    public function countTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
}
?>