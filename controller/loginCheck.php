<?php
session_start();
require_once('../model/userModel.php');

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {
        header('location: ../view/login.php?error=null');
    } else {
        $user = login($username, $password);
        if ($user) {
            setcookie('status', 'true', time() + 3600, '/');
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('location: ../view/home.php');
        } else {
            header('location: ../view/login.php?error=invalid');
        }
    }
}

/**
 * ============================================
 * @author MILTON
 * @task Feature 1: Authentication - Sign-in with Session and Cookie
 * @date 2025-12-29
 * ============================================
 */
?>