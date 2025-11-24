<?php
// models/User.php - simple JSON-backed user store
class User {
    private $file;

    public function __construct($file = null) {
        $this->file = $file ? $file : __DIR__ . '/../data/users.json';
        if(!file_exists(dirname($this->file))) {
            mkdir(dirname($this->file), 0755, true);
        }

        if(!file_exists($this->file)) {
            // create default admin user
            $default = [
                ['username' => 'admin', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'role' => 'admin']
            ];
            file_put_contents($this->file, json_encode($default, JSON_PRETTY_PRINT));
        }
    }

    private function readAll() {
        $json = file_get_contents($this->file);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    private function writeAll($arr) {
        file_put_contents($this->file, json_encode($arr, JSON_PRETTY_PRINT));
    }

    public function findByUsername($username) {
        $users = $this->readAll();
        foreach($users as $u) {
            if(strtolower($u['username']) === strtolower($username)) return $u;
        }
        return null;
    }

    public function create($username, $password) {
        $users = $this->readAll();
        // prevent duplicate usernames
        foreach($users as $u) {
            if(strtolower($u['username']) === strtolower($username)) return false;
        }

        $users[] = ['username' => $username, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => 'user'];
        $this->writeAll($users);
        return true;
    }
}

?>
