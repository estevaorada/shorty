<?php

require_once 'database.php';

class Users {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function addUser($email, $password, $signup_key) {
        if ($signup_key !== SIGNUP_KEY) {
            throw new Exception('Chave de inscrição inválida.');
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'password_hash' => $password_hash,
        ]);

        return $this->db->lastInsertId();
    }

    public function add_user($email, $password, $signup_key) {
        return $this->addUser($email, $password, $signup_key);
    }

    public function authenticate($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        } else {
            return false;
        }
    }
}