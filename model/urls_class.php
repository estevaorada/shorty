<?php

require_once __DIR__ . '/database.php';

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

    public function getUrlsByUser($userId, $search = '') {
        $query = "SELECT id, short_code, long_url, clicks_count, expires_at, created_at
                  FROM urls
                  WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if ($search !== '') {
            $query .= " AND (short_code LIKE :search_short_code OR long_url LIKE :search_long_url)";
            $searchValue = '%' . $search . '%';
            $params['search_short_code'] = $searchValue;
            $params['search_long_url'] = $searchValue;
        }

        $query .= " ORDER BY created_at DESC, id DESC";
        return $this->executeQuery($query, $params)->fetchAll();
    }

    public function getTopUrlsByUser($userId, $limit = 10) {
        $query = "SELECT id, short_code, long_url, clicks_count, created_at
                  FROM urls
                  WHERE user_id = :user_id
                  ORDER BY clicks_count DESC, created_at DESC, id DESC
                  LIMIT " . (int) $limit;
        $statement = $this->executeQuery($query, ['user_id' => $userId]);
        return $statement->fetchAll();
    }

    public function getRecentUrlsByUser($userId, $limit = 10) {
        $query = "SELECT id, short_code, long_url, clicks_count, created_at
                  FROM urls
                  WHERE user_id = :user_id
                  ORDER BY created_at DESC, id DESC
                  LIMIT " . (int) $limit;
        $statement = $this->executeQuery($query, ['user_id' => $userId]);
        return $statement->fetchAll();
    }

    public function createShortUrl($userId, $longUrl, $slug = '') {
        $attempts = $slug === '' ? 100 : 1;

        for ($attempt = 0; $attempt < $attempts; $attempt++) {
            $shortCode = $slug !== '' ? $slug : $this->generateShortCode();
            $query = "INSERT INTO urls (short_code, long_url, user_id)
                      VALUES (:short_code, :long_url, :user_id)";

            try {
                $statement = $this->db->prepare($query);
                $statement->execute([
                    'short_code' => $shortCode,
                    'long_url' => $longUrl,
                    'user_id' => $userId,
                ]);

                return [
                    'id' => (int) $this->db->lastInsertId(),
                    'short_code' => $shortCode,
                ];
            } catch (PDOException $exception) {
                if ($exception->getCode() !== '23000') {
                    throw $exception;
                }

                if ($slug !== '') {
                    throw new RuntimeException('Este slug já está em uso. Escolha outro.');
                }
            }
        }

        throw new RuntimeException('Não foi possível gerar um slug disponível. Tente novamente.');
    }

    private function generateShortCode() {
        return 'link-' . bin2hex(random_bytes(5));
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