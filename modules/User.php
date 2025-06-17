<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

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

        if (!$user['is_verified']) {
            $_SESSION['error'] = "Пожалуйста, подтвердите email перед входом.";
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            debug_log('Пароль неверный');
            return false;
        }

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
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return false;
        }
        $stmt->close();

        $token = bin2hex(random_bytes(16));
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (name, email, phone, password, birthdate, role, created_at, is_verified, verification_token)
                                    VALUES (?, ?, ?, ?, ?, 'user', NOW(), 0, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $hashed, $birthdate, $token);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            debug_log("Зарегистрирован: $email, токен: $token");
            $this->sendVerificationEmail($email, $token);
        }

        return $result;
    }

    private function sendVerificationEmail($email, $token) {
        $verifyLink = BASE_URL . "verify.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->Port       = $_ENV['SMTP_PORT'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = $_ENV['SMTP_SECURE'];

            $mail->setFrom($_ENV['SMTP_USER'], 'Подтверждение регистрации');
            $mail->addAddress($email);

            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Подтверждение регистрации';
            $mail->Body    = "Здравствуйте!\n\nПодтвердите регистрацию, перейдя по ссылке:\n$verifyLink\n\nЕсли вы не регистрировались — проигнорируйте это письмо.";

            debug_log("Письмо подтверждения -> $email");
            debug_log("Тема: {$mail->Subject}");
            debug_log("Тело:\n{$mail->Body}");

            $mail->send();
            debug_log("Письмо успешно отправлено.");
        } catch (Exception $e) {
            debug_log("Ошибка отправки письма: " . $mail->ErrorInfo);
        }
    }
}