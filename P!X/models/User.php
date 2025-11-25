<?php
// models/User.php
require_once 'models/QueryBuilder.php';

class User {
    private $conn;
    private $qb;
    private $table_name = "User";

    public $id_user;
    public $username;
    public $email;
    public $password;
    public $nama_lengkap;
    public $no_telpon;
    public $tanggal_lahir;
    public $alamat;
    public $tanggal_daftar;
    public $status_akun;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // Verify Login
    public function verifyLogin($username, $password) {
        $user = $this->findByUsername($username);
        
        if ($user && $user['status_akun'] === 'aktif') {
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }

    // CREATE - Register User Baru
    public function create() {
        $data = [
            'username' => htmlspecialchars(strip_tags($this->username)),
            'email' => htmlspecialchars(strip_tags($this->email)),
            'password' => password_hash($this->password, PASSWORD_DEFAULT),
            'nama_lengkap' => htmlspecialchars(strip_tags($this->nama_lengkap)),
            'no_telpon' => htmlspecialchars(strip_tags($this->no_telpon)),
            'tanggal_lahir' => htmlspecialchars(strip_tags($this->tanggal_lahir)),
            'alamat' => htmlspecialchars(strip_tags($this->alamat)),
            'status_akun' => 'aktif'
        ];

        return $this->qb->reset()->table($this->table_name)->insert($data);
    }

    // Find User by Username
    public function findByUsername($username) {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('username', '=', $username)
            ->first();
        
        return $row;
    }

    // Find User by Email
    public function findByEmail($email) {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('email', '=', $email)
            ->first();
        
        return $row;
    }

    // Check if Username exists
    public function usernameExists($username, $exclude_id = null) {
        $qb = $this->qb->reset()
            ->table($this->table_name)
            ->where('username', '=', $username);
        
        if ($exclude_id) {
            $qb->where('id_user', '!=', $exclude_id);
        }
        
        return $qb->count() > 0;
    }

    // Check if Email exists
    public function emailExists($email, $exclude_id = null) {
        $qb = $this->qb->reset()
            ->table($this->table_name)
            ->where('email', '=', $email);
        
        if ($exclude_id) {
            $qb->where('id_user', '!=', $exclude_id);
        }
        
        return $qb->count() > 0;
    }

    // READ ONE User by ID
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('id_user', '=', $this->id_user)
            ->first();

        if ($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->nama_lengkap = $row['nama_lengkap'];
            $this->no_telpon = $row['no_telpon'];
            $this->tanggal_lahir = $row['tanggal_lahir'];
            $this->alamat = $row['alamat'];
            $this->tanggal_daftar = $row['tanggal_daftar'];
            $this->status_akun = $row['status_akun'];
            return true;
        }
        
        return false;
    }

    // UPDATE User Profile
    public function update() {
        $data = [
            'username' => htmlspecialchars(strip_tags($this->username)),
            'email' => htmlspecialchars(strip_tags($this->email)),
            'nama_lengkap' => htmlspecialchars(strip_tags($this->nama_lengkap)),
            'no_telpon' => htmlspecialchars(strip_tags($this->no_telpon)),
            'tanggal_lahir' => htmlspecialchars(strip_tags($this->tanggal_lahir)),
            'alamat' => htmlspecialchars(strip_tags($this->alamat))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_user', '=', htmlspecialchars(strip_tags($this->id_user)))
            ->update($data);
    }
}
?>