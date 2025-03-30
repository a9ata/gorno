<?php
require_once __DIR__ . '/../config/db.php';


$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $name = trim($_POST['name']);
  $question = trim($_POST['question_text']);

  if ($email && $name && $question) {
    // Проверка: есть ли такой пользователь
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
      $user_id = $user['id'];

      // Сохраняем вопрос
      $insert = $conn->prepare("INSERT INTO questions (user_id, question_text, created_at) VALUES (?, ?, NOW())");
      $insert->execute([$user_id, $question]);

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
  <input type="text" name="name" placeholder="Имя пользователя" required>
  <input type="email" name="email" placeholder="Почта" required>
  <textarea name="question" placeholder="Введите свой вопрос" required></textarea>
  <button type="submit">Отправить</button>
</form>
