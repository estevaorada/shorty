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

    public function getStatsByUser($urlId, $userId) {
        $urlStatement = $this->executeQuery(
            'SELECT id, short_code, long_url, created_at
             FROM urls
             WHERE id = :url_id AND user_id = :user_id
             LIMIT 1',
            [
                'url_id' => $urlId,
                'user_id' => $userId,
            ]
        );
        $url = $urlStatement->fetch();

        if (!$url) {
            return null;
        }

        $stats = [
            'url' => $url,
            'total_clicks' => $this->getClickCount($urlId),
            'by_source' => $this->getClickBreakdown($urlId, 'utm_source'),
            'by_medium' => $this->getClickBreakdown($urlId, 'utm_medium'),
            'by_campaign' => $this->getClickBreakdown($urlId, 'utm_campaign'),
            'daily' => $this->getDailyClicks($urlId),
        ];

        return $stats;
    }

    private function getClickCount($urlId) {
        $statement = $this->executeQuery(
            'SELECT COUNT(*) FROM click_logs WHERE url_id = :url_id',
            ['url_id' => $urlId]
        );
        return (int) $statement->fetchColumn();
    }

    private function getClickBreakdown($urlId, $column) {
        $allowedColumns = ['utm_source', 'utm_medium', 'utm_campaign'];
        if (!in_array($column, $allowedColumns, true)) {
            throw new InvalidArgumentException('Agrupamento inválido.');
        }

        $statement = $this->executeQuery(
            "SELECT COALESCE(NULLIF($column, ''), 'Sem UTM') AS label, COUNT(*) AS total
             FROM click_logs
             WHERE url_id = :url_id
             GROUP BY label
             ORDER BY total DESC, label ASC",
            ['url_id' => $urlId]
        );
        return $statement->fetchAll();
    }

    private function getDailyClicks($urlId) {
        $statement = $this->executeQuery(
            "SELECT DATE(clicked_at) AS label, COUNT(*) AS total
             FROM click_logs
             WHERE url_id = :url_id
             GROUP BY DATE(clicked_at)
             ORDER BY label ASC",
            ['url_id' => $urlId]
        );
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
        return bin2hex(random_bytes(5));
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