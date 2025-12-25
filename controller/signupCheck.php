<?php
session_start();
require_once('../model/userModel.php');
require_once('../model/tournamentModel.php');

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if ($name == "" || $username == "" || $email == "" || $password == "" || $confirmPassword == "") {
        header('location: ../view/signup.php?error=null');
    } elseif ($password !== $confirmPassword) {
        header('location: ../view/signup.php?error=mismatch');
    } elseif (isUnique($username, $email)) {
        $user = ['name' => $name, 'username' => $username, 'email' => $email, 'password' => $password];
        if (signup($user)) {
            logActivity("New user registered: $username");
            header('location: ../view/login.php?success=registered');
        } else {
            header('location: ../view/signup.php?error=db_error');
        }
    } else {
        header('location: ../view/signup.php?error=not_unique');
    }
}
?>