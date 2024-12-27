<?php
class Database {
    private $db;

    public function __construct() {
        $this->db = new PDO('sqlite:data.db');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTable();
    }

    private function createTable() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            description TEXT
        )");
    }

    public function getConnection() {
        return $this->db;
    }
}