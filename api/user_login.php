<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'E-mail e senha são obrigatórios.']);
        exit;
    }
    require_once '../model/users_class.php';
    $users = new Users();
    $user = $users->authenticate($email, $password);
    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        echo json_encode(['success' => true, 'message' => 'Login bem-sucedido.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'E-mail ou senha inválidos.']);
    }
}else{
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
    exit;
}
?>
