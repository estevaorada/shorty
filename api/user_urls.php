<?php

header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado.',
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido.',
    ]);
    exit;
}

$search = trim($_GET['search'] ?? '');

require_once '../model/urls_class.php';

try {
    $urls = new Urls();
    $items = $urls->getUrlsByUser((int) $_SESSION['user_id'], $search);

    echo json_encode([
        'success' => true,
        'items' => $items,
    ]);
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Não foi possível carregar suas URLs.',
    ]);
}