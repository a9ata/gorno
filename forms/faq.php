<?php
require_once __DIR__ . '/../config/db.php';


$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $question = trim($_POST['question_text'] ?? '');

if ($email && $name && $question) {
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    $user_id = $user['id'];

    $insert = $conn->prepare("INSERT INTO questions (user_id, question_text, created_at) VALUES (?, ?, NOW())");
    $insert->bind_param("is", $user_id, $question);
    $insert->execute();

    $message = "<p class='success'>Ваш вопрос отправлен. Спасибо!</p>";
  } else {
    $message = "<p class='error'>Пользователь с таким email не найден. Пожалуйста, зарегистрируйтесь.</p>";
    }
  } else {
  $message = "<p class='error'>Пожалуйста, заполните все поля.</p>";
  }
}
?>

<?= $message ?>

<form action="" method="POST">
  <input type="text" name="name" placeholder="Имя" required>
  <input type="email" name="email" placeholder="Почта" required>
  <textarea name="question_text" placeholder="Введите свой вопрос" required></textarea>
  <button type="submit">Отправить</button>
</form>
