<?php

require_once 'models/BaseModel.php';

class User extends BaseModel {
    use Searchable;
    
    // Properties
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

    // Implementation of abstract methods
    protected function getTableName() {
        return "User";
    }
    
    protected function getPrimaryKey() {
        return "id_user";
    }
    
    protected function getSearchableFields() {
        return ['username', 'email', 'nama_lengkap'];
    }
    
    protected function prepareData() {
        return [
            'username' => $this->sanitize($this->username),
            'email' => $this->sanitize($this->email),
            'nama_lengkap' => $this->sanitize($this->nama_lengkap),
            'no_telpon' => $this->sanitize($this->no_telpon),
            'tanggal_lahir' => $this->sanitize($this->tanggal_lahir),
            'alamat' => $this->sanitize($this->alamat)
        ];
    }
    
    // Polymorphism - Override create untuk hash password
    public function create() {
        $data = $this->prepareData();
        $data['password'] = password_hash($this->password, PASSWORD_DEFAULT);
        $data['status_akun'] = 'aktif';
        
        return $this->qb->reset()
            ->table($this->getTableName())
            ->insert($data);
    }
    
    // Authentication methods
    public function verifyLogin($username, $password) {
        $user = $this->findByUsername($username);
        
        if ($user && $user['status_akun'] === 'aktif') {
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }

    public function findByUsername($username) {
        $row = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->where('username', '=', $username)
            ->first();
        
        return $row;
    }

    public function findByEmail($email) {
        $row = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->where('email', '=', $email)
            ->first();
        
        return $row;
    }

    public function usernameExists($username, $exclude_id = null) {
        $qb = $this->qb->reset()
            ->table($this->getTableName())
            ->where('username', '=', $username);
        
        if ($exclude_id) {
            $qb->where($this->getPrimaryKey(), '!=', $exclude_id);
        }
        
        return $qb->count() > 0;
    }

    public function emailExists($email, $exclude_id = null) {
        $qb = $this->qb->reset()
            ->table($this->getTableName())
            ->where('email', '=', $email);
        
        if ($exclude_id) {
            $qb->where($this->getPrimaryKey(), '!=', $exclude_id);
        }
        
        return $qb->count() > 0;
    }

    // Polymorphism - Override readOne
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->where($this->getPrimaryKey(), '=', $this->id_user)
            ->first();

        if ($row) {
            $this->populateFromArray($row);
            return true;
        }
        
        return false;
    }
}
?>