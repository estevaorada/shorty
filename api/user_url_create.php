<?php

header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
    exit;
}

$longUrl = trim($_POST['long_url'] ?? '');
$slug = trim($_POST['slug'] ?? '');

if (!filter_var($longUrl, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Informe uma URL válida.']);
    exit;
}

if ($slug !== '' && (!preg_match('/^[a-zA-Z0-9_-]+$/', $slug) || strlen($slug) > 16)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'O slug deve ter até 16 caracteres e usar apenas letras, números, hífen ou sublinhado.']);
    exit;
}

require_once '../model/urls_class.php';

try {
    $urls = new Urls();
    $created = $urls->createShortUrl((int) $_SESSION['user_id'], $longUrl, $slug);
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $basePath = rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))), '/');
    $shortUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $basePath . '/go.php?code=' . rawurlencode($created['short_code']);

    echo json_encode([
        'success' => true,
        'message' => 'URL encurtada com sucesso.',
        'item' => [
            'id' => $created['id'],
            'short_code' => $created['short_code'],
            'short_url' => $shortUrl,
            'long_url' => $longUrl,
            'clicks_count' => 0,
        ],
    ]);
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Não foi possível encurtar a URL.']);
} catch (RuntimeException $exception) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Não foi possível encurtar a URL.']);
}