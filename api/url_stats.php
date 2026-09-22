<?php

header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$urlId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$urlId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'URL inválida.']);
    exit;
}

require_once __DIR__ . '/../model/urls_class.php';

try {
    $urls = new Urls();
    $stats = $urls->getStatsByUser($urlId, (int) $_SESSION['user_id']);

    if (!$stats) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'URL não encontrada.']);
        exit;
    }

    echo json_encode(['success' => true] + $stats);
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Não foi possível carregar as estatísticas.']);
}