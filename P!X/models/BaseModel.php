<?php
// models/BaseModel.php - Abstract Base Class untuk semua model
abstract class BaseModel {
    protected $conn;
    protected $qb;
    protected $table_name;
    
    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }
    
    // Abstract methods - harus diimplementasikan oleh child class
    abstract protected function getTableName();
    abstract protected function getPrimaryKey();
    
    // Template Method Pattern - method yang menggunakan abstract methods
    public function findById($id) {
        $primaryKey = $this->getPrimaryKey();
        $row = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->where($primaryKey, '=', $id)
            ->first();
        
        if ($row) {
            $this->populateFromArray($row);
            return true;
        }
        return false;
    }
    
    // Method readOne - untuk compatibility dengan existing code
    public function readOne() {
        $primaryKey = $this->getPrimaryKey();
        $id = $this->$primaryKey;
        
        $row = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->where($primaryKey, '=', $id)
            ->first();

        if ($row) {
            $this->populateFromArray($row);
            return true;
        }
        
        return false;
    }
    
    // Polymorphism - dapat di-override oleh child class
    public function create() {
        $data = $this->prepareData();
        return $this->qb->reset()
            ->table($this->getTableName())
            ->insert($data);
    }
    
    public function update() {
        $data = $this->prepareData();
        $primaryKey = $this->getPrimaryKey();
        $id = $this->$primaryKey;
        
        return $this->qb->reset()
            ->table($this->getTableName())
            ->where($primaryKey, '=', $id)
            ->update($data);
    }
    
    public function delete() {
        $primaryKey = $this->getPrimaryKey();
        $id = $this->$primaryKey;
        
        return $this->qb->reset()
            ->table($this->getTableName())
            ->where($primaryKey, '=', $id)
            ->delete();
    }
    
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->get();
        
        return $stmt;
    }
    
    public function countTotal() {
        return $this->qb->reset()
            ->table($this->getTableName())
            ->count();
    }
    
    // Helper methods
    protected function sanitize($value) {
        return htmlspecialchars(strip_tags($value));
    }
    
    // Abstract method untuk prepare data - harus diimplementasikan child
    abstract protected function prepareData();
    
    // Populate object properties from array
    protected function populateFromArray($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}

// Trait untuk search functionality - dapat digunakan oleh model yang membutuhkan
trait Searchable {
    public function search($keyword, $fields = []) {
        $qb = $this->qb->reset()->table($this->getTableName());
        
        if (empty($fields)) {
            $fields = $this->getSearchableFields();
        }
        
        $qb->whereLike($fields[0], $keyword);
        
        for ($i = 1; $i < count($fields); $i++) {
            $qb->orWhereLike($fields[$i], $keyword);
        }
        
        return $qb->get();
    }
    
    // Override di child class untuk specify searchable fields
    protected function getSearchableFields() {
        return ['*'];
    }
}

// Trait untuk soft delete functionality
trait SoftDeletable {
    protected $deletedAtColumn = 'deleted_at';
    
    public function softDelete() {
        $primaryKey = $this->getPrimaryKey();
        $id = $this->$primaryKey;
        
        return $this->qb->reset()
            ->table($this->getTableName())
            ->where($primaryKey, '=', $id)
            ->update([$this->deletedAtColumn => date('Y-m-d H:i:s')]);
    }
    
    public function restore() {
        $primaryKey = $this->getPrimaryKey();
        $id = $this->$primaryKey;
        
        return $this->qb->reset()
            ->table($this->getTableName())
            ->where($primaryKey, '=', $id)
            ->update([$this->deletedAtColumn => null]);
    }
}
?>