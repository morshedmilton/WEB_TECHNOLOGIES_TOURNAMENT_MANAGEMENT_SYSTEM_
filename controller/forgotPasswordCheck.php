<?php
session_start();
require_once('../model/userModel.php');

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    if ($email == "") {
        echo "<script>alert('Please enter your email!'); window.location.href='../view/forgotPassword.php';</script>";
    } else {
        // Simple verification that email exists (Basic Logic)
        $con = getConnection();
        $safe_email = mysqli_real_escape_string($con, $email);
        $sql = "SELECT * FROM users WHERE email='$safe_email'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Simulation of email sending
            echo "<script>alert('A reset link has been sent to your email (Simulated)!'); window.location.href='../view/login.php';</script>";
        } else {
            echo "<script>alert('Email not found!'); window.location.href='../view/forgotPassword.php';</script>";
        }
    }
} else {
    header('location: ../view/forgotPassword.php');
}
?>