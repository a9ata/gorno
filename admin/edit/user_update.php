<?php
// admin/edit/user_update.php — только логика UPDATE
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';
if (!isAdmin()) {
    header('Location:/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)($_POST['edit_id'] ?? 0);
    $name      = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']) ?: null;
    $birthdate = trim($_POST['birthdate']) ?: null;
    $role      = $_POST['role'];
    $password  = trim($_POST['password']);

    if ($id && $name && $email) {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
              "UPDATE users
                  SET name=?, email=?, phone=?, birthdate=?, password=?, role=?
                WHERE id=?"
            );
            $stmt->bind_param(
              'ssssssi', $name, $email, $phone, $birthdate, $hash, $role, $id
            );
        } else {
            $stmt = $conn->prepare(
              "UPDATE users
                  SET name=?, email=?, phone=?, birthdate=?, role=?
                WHERE id=?"
            );
            $stmt->bind_param(
              'sssssi', $name, $email, $phone, $birthdate, $role, $id
            );
        }
        $stmt->execute();
    }
}

header('Location: /admin/index.php?section=users');
exit;