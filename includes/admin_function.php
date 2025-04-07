<?php
function isAdmin(): bool {
    session_start();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}