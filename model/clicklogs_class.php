<?php

require_once __DIR__ . '/database.php';

class ClickLogs {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function getAllClickLogs() {
        $query = "SELECT * FROM clicklogs";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getClickLogById($id) {
        $query = "SELECT * FROM clicklogs WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt;
    }

    public function createClickLog($url_id, $user_id) {
        $query = "INSERT INTO clicklogs (url_id, user_id) VALUES (:url_id, :user_id)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['url_id' => $url_id, 'user_id' => $user_id]);
        return $stmt;
    }
}
