<?php
session_start();

// Collect username and password from input
$username = trim($_REQUEST['username']);
$password = trim($_REQUEST['password']);

if ($username == "" || $password == "") {
    header('location: ../view/login.php?error=invalid_user');
} else {
    // Trick: if username and password are same, allow login
    if ($username == $password) {
        // Set status cookie which checks our view pages
        setcookie('status', 'true', time() + 3600, '/');
        $_SESSION['username'] = $username;

        // Redirect to dashboard
        header('location: ../view/home.php');
    } else {
        header('location: ../view/login.php?error=invalid_user');
    }
}
?>