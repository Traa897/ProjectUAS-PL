<?php

class Validator {
    private $errors = [];
    private $data = [];
    
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field][] = $message ?? "Field {$field} wajib diisi";
        }
        return $this;
    }
    
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?? "Email tidak valid";
        }
        return $this;
    }
    
    public function min($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field][] = $message ?? "Field {$field} minimal {$length} karakter";
        }
        return $this;
    }
    
    public function max($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field][] = $message ?? "Field {$field} maksimal {$length} karakter";
        }
        return $this;
    }
    
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field][] = $message ?? "Field {$field} harus berupa angka";
        }
        return $this;
    }
    
    public function between($field, $min, $max, $message = null) {
        if (isset($this->data[$field])) {
            $value = (float)$this->data[$field];
            if ($value < $min || $value > $max) {
                $this->errors[$field][] = $message ?? "Field {$field} harus antara {$min} dan {$max}";
            }
        }
        return $this;
    }
    
    public function url($field, $message = null) {
        if (isset($this->data[$field]) && trim($this->data[$field]) !== '' && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field][] = $message ?? "URL tidak valid";
        }
        return $this;
    }
    
    public function date($field, $format = 'Y-m-d', $message = null) {
        if (isset($this->data[$field]) && trim($this->data[$field]) !== '') {
            $d = DateTime::createFromFormat($format, $this->data[$field]);
            if (!$d || $d->format($format) !== $this->data[$field]) {
                $this->errors[$field][] = $message ?? "Format tanggal tidak valid";
            }
        }
        return $this;
    }
    
    public function unique($field, $table, $column, $db, $exceptId = null, $message = null) {
        if (isset($this->data[$field]) && trim($this->data[$field]) !== '') {
            $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
            
            if ($exceptId !== null) {
                $primaryKey = $this->guessPrimaryKey($table);
                $query .= " AND {$primaryKey} != :exceptId";
            }
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':value', $this->data[$field]);
            
            if ($exceptId !== null) {
                $stmt->bindParam(':exceptId', $exceptId);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                $this->errors[$field][] = $message ?? "Field {$field} sudah digunakan";
            }
        }
        return $this;
    }
    
    public function custom($field, $callback, $message = null) {
        if (isset($this->data[$field])) {
            if (!$callback($this->data[$field])) {
                $this->errors[$field][] = $message ?? "Field {$field} tidak valid";
            }
        }
        return $this;
    }
    
    public function fails() {
        return !empty($this->errors);
    }
    
    public function passes() {
        return empty($this->errors);
    }
    
    public function errors() {
        return $this->errors;
    }
    
    public function firstError($field = null) {
        if ($field) {
            return $this->errors[$field][0] ?? null;
        }
        
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }
        
        return null;
    }
    
    public function allErrors() {
        $messages = [];
        foreach ($this->errors as $fieldErrors) {
            $messages = array_merge($messages, $fieldErrors);
        }
        return $messages;
    }
    
    private function guessPrimaryKey($table) {
        $primaryKeys = [
            'Film' => 'id_film',
            'User' => 'id_user',
            'Admin' => 'id_admin',
            'Bioskop' => 'id_bioskop',
            'Jadwal_Tayang' => 'id_tayang',
            'Genre' => 'id_genre',
        ];
        
        return $primaryKeys[$table] ?? 'id';
    }
}
?>