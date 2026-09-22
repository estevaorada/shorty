<?php

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido.',
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$signup_key = $_POST['signup_key'] ?? '';

if ($email === '' || $password === '' || $signup_key === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'E-mail, senha e chave de inscrição são obrigatórios.',
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Informe um e-mail válido.',
    ]);
    exit;
}

require_once '../model/users_class.php';

try {
    $users = new Users();
    $users->addUser($email, $password, $signup_key);

    echo json_encode([
        'success' => true,
        'message' => 'Cadastro realizado com sucesso. Você já pode entrar.',
    ]);
} catch (Exception $exception) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage(),
    ]);
}
