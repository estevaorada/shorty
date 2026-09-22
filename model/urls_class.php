<?php

require_once 'database.php';

class Urls {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    private function executeQuery($query, $params = []) {
        $statement = $this->db->prepare($query);
        $statement->execute($params);
        return $statement;
    }

    public function getAllUrls() {
        $query = "SELECT * FROM urls";
        return $this->executeQuery($query);
    }

    public function getUrlById($id) {
        $query = "SELECT * FROM urls WHERE id = :id";
        return $this->executeQuery($query, ['id' => $id]);
    }

    public function createUrl($original_url, $short_url) {
        $query = "INSERT INTO urls (original_url, short_url) VALUES (:original_url, :short_url)";
        return $this->executeQuery($query, ['original_url' => $original_url, 'short_url' => $short_url]);
    }

    public function updateUrl($id, $original_url, $short_url) {
        $query = "UPDATE urls SET original_url = :original_url, short_url = :short_url WHERE id = :id";
        return $this->executeQuery($query, ['id' => $id, 'original_url' => $original_url, 'short_url' => $short_url]);
    }

    public function deleteUrl($id) {
        $query = "DELETE FROM urls WHERE id = :id";
        return $this->executeQuery($query, ['id' => $id]);
    }
}