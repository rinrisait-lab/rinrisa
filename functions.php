<?php
require_once 'config.php';

/* ================= LOGIN CHECK ================= */

function isLoggedIn() {
    return isset($_SESSION['user']);
}

/* ================= LOGIN FUNCTION ================= */

function login($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // If password stored using MD5
        if (md5($password) === $user['password']) {
            $_SESSION['user'] = $user;
            return true;
        }
    }

    return false;
}

/* ================= LOGOUT FUNCTION ================= */

function logout() {
    session_unset();
    session_destroy();
}

/* ================= SANITIZE ================= */

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
