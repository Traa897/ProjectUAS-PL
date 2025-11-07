<?php
// models/QueryBuilder.php

class QueryBuilder {
    private $pdo;
    private $table;
    private $query;
    private $bindings = [];
    private $select = '*';
    private $joins = [];
    private $wheres = [];
    private $orderBy = [];
    private $groupBy = [];
    private $limit;
    private $offset;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Set table yang akan digunakan
     */
    public function table($table) {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Set kolom yang akan diselect
     */
    public function select($columns = '*') {
        if (is_array($columns)) {
            $this->select = implode(', ', $columns);
        } else {
            $this->select = $columns;
        }
        return $this;
    }
    
    /**
     * Join table lain
     */
    public function join($table, $first, $operator, $second, $type = 'INNER') {
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }
    
    /**
     * Left join
     */
    public function leftJoin($table, $first, $operator, $second) {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }
    
    /**
     * Where clause
     */
    public function where($column, $operator, $value = null) {
        // Jika hanya 2 parameter, operator default adalah '='
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $placeholder = ':where_' . count($this->bindings);
        $this->wheres[] = "$column $operator $placeholder";
        $this->bindings[$placeholder] = $value;
        
        return $this;
    }
    
    /**
     * Where LIKE clause
     */
    public function whereLike($column, $value) {
        $placeholder = ':like_' . count($this->bindings);
        $this->wheres[] = "$column LIKE $placeholder";
        $this->bindings[$placeholder] = "%$value%";
        
        return $this;
    }
    
    /**
     * Or Where LIKE clause
     */
    public function orWhereLike($column, $value) {
        $placeholder = ':orlike_' . count($this->bindings);
        
        if (empty($this->wheres)) {
            $this->wheres[] = "$column LIKE $placeholder";
        } else {
            $lastIndex = count($this->wheres) - 1;
            $this->wheres[$lastIndex] .= " OR $column LIKE $placeholder";
        }
        
        $this->bindings[$placeholder] = "%$value%";
        
        return $this;
    }
    
    /**
     * Order By
     */
    public function orderBy($column, $direction = 'ASC') {
        $this->orderBy[] = "$column $direction";
        return $this;
    }
    
    /**
     * Group By
     */
    public function groupBy($column) {
        $this->groupBy[] = $column;
        return $this;
    }
    
    /**
     * Limit
     */
    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Offset
     */
    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * Build query SELECT
     */
    private function buildSelectQuery() {
        $query = "SELECT {$this->select} FROM {$this->table}";
        
        // Tambahkan JOINs
        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }
        
        // Tambahkan WHERE
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        
        // Tambahkan GROUP BY
        if (!empty($this->groupBy)) {
            $query .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }
        
        // Tambahkan ORDER BY
        if (!empty($this->orderBy)) {
            $query .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        
        // Tambahkan LIMIT
        if ($this->limit !== null) {
            $query .= " LIMIT {$this->limit}";
        }
        
        // Tambahkan OFFSET
        if ($this->offset !== null) {
            $query .= " OFFSET {$this->offset}";
        }
        
        return $query;
    }
    
    /**
     * Execute dan return PDOStatement
     */
    public function get() {
        $query = $this->buildSelectQuery();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($this->bindings);
        return $stmt;
    }
    
    /**
     * Get first result
     */
    public function first() {
        $this->limit(1);
        $stmt = $this->get();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Count results
     */
    public function count() {
        $originalSelect = $this->select;
        $this->select = 'COUNT(*) as total';
        
        $query = $this->buildSelectQuery();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($this->bindings);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->select = $originalSelect;
        
        return (int) $result['total'];
    }
    
    /**
     * Get average
     */
    public function avg($column) {
        $this->select = "AVG($column) as average";
        $query = $this->buildSelectQuery();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($this->bindings);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['average'];
    }
    
    /**
     * INSERT data
     */
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        
        $bindings = [];
        foreach ($data as $key => $value) {
            $bindings[":$key"] = $value;
        }
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($bindings);
    }
    
    /**
     * UPDATE data
     */
    public function update($data) {
        $sets = [];
        $bindings = [];
        
        foreach ($data as $key => $value) {
            $sets[] = "$key = :set_$key";
            $bindings[":set_$key"] = $value;
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $sets);
        
        // Tambahkan WHERE
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
            $bindings = array_merge($bindings, $this->bindings);
        }
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($bindings);
    }
    
    /**
     * DELETE data
     */
    public function delete() {
        $query = "DELETE FROM {$this->table}";
        
        // Tambahkan WHERE
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($this->bindings);
    }
    
    /**
     * Find by ID
     */
    public function find($id) {
        return $this->where('id', $id)->first();
    }
    
    /**
     * Get query for debugging
     */
    public function toSql() {
        return $this->buildSelectQuery();
    }
    
    /**
     * Reset builder
     */
    public function reset() {
        $this->query = null;
        $this->bindings = [];
        $this->select = '*';
        $this->joins = [];
        $this->wheres = [];
        $this->orderBy = [];
        $this->groupBy = [];
        $this->limit = null;
        $this->offset = null;
        return $this;
    }
}
?>