<?php
require_once __DIR__ . '/../config/db.php';

class User {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    
    
        if (!$user) {
            debug_log('Пользователь не найден.');
            return false;
        }
    
        if (!isset($user['password'])) {
            debug_log('Поле password отсутствует.');
            return false;
        }
    
        if (!is_string($user['password'])) {
            debug_log('Поле password не строка: ' . gettype($user['password']));
            return false;
        }
    
        if (!password_verify($password, $user['password'])) {
            debug_log('пароль неверный');
            return false;
        }
    
        // Авторизация успешна
        $_SESSION['name']      = $user['name'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['phone']     = $user['phone'];
        $_SESSION['birthdate'] = $user['birthdate'] ?? null;
        $_SESSION['role']      = $user['role'] ?? 'user';
        $_SESSION['id_user']   = $user['id'] ?? null;
    
        return true;
    }
    
    

    public function register($name, $email, $phone, $password, $birthdate) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return false; // уже существует
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, phone, password, birthdate, role, created_at)
                                    VALUES (?, ?, ?, ?, ?, 'user', NOW())");
        return $stmt->execute([$name, $email, $phone, $hashed, $birthdate]);
    }
}