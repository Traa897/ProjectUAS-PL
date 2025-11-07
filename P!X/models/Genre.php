<?php
// models/Genre.php
require_once 'models/QueryBuilder.php';

class Genre {
    private $conn;
    private $qb;
    private $table_name = "genres";

    public $id;
    public $name;
    public $slug;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->orderBy('name', 'ASC')
            ->get();
        
        return $stmt;
    }

    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('id', '=', $this->id)
            ->first();

        if ($row) {
            $this->name = $row['name'];
            $this->slug = $row['slug'];
            return true;
        }
        
        return false;
    }

    public function findBySlug($slug) {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('slug', '=', $slug)
            ->first();

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->slug = $row['slug'];
            return true;
        }
        
        return false;
    }

    public function countMovies() {
        $stmt = $this->qb->reset()
            ->table('genres g')
            ->select('g.*, COUNT(m.id) as movie_count')
            ->leftJoin('movies m', 'g.id', '=', 'm.genre_id')
            ->groupBy('g.id')
            ->orderBy('g.name', 'ASC')
            ->get();
        
        return $stmt;
    }

    public function create() {
        $data = [
            'name' => htmlspecialchars(strip_tags($this->name)),
            'slug' => htmlspecialchars(strip_tags($this->slug))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->insert($data);
    }

    public function update() {
        $data = [
            'name' => htmlspecialchars(strip_tags($this->name)),
            'slug' => htmlspecialchars(strip_tags($this->slug))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id', '=', htmlspecialchars(strip_tags($this->id)))
            ->update($data);
    }

    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id', '=', htmlspecialchars(strip_tags($this->id)))
            ->delete();
    }
}
?>