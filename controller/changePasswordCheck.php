<?php
session_start();
require_once('../model/userModel.php');

if (isset($_POST['submit'])) {
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmNewPassword = trim($_POST['confirmNewPassword']);
    $username = $_SESSION['username'];

    // ১. খালি ফিল্ড চেক
    if ($currentPassword == "" || $newPassword == "" || $confirmNewPassword == "") {
        header('location: ../view/changePassword.php?error=null');
        exit();
    }

    // ২. নতুন পাসওয়ার্ড ম্যাচিং চেক
    if ($newPassword !== $confirmNewPassword) {
        header('location: ../view/changePassword.php?error=mismatch');
        exit();
    }

    // ৩. বর্তমান পাসওয়ার্ড সঠিক কি না চেক
    $user = login($username, $currentPassword);

    if ($user) {
        // ৪. পাসওয়ার্ড আপডেট করা
        if (updatePassword($user['id'], $newPassword)) {
            header('location: ../view/profile.php?success=password_changed');
        } else {
            header('location: ../view/changePassword.php?error=db_error');
        }
    } else {
        header('location: ../view/changePassword.php?error=invalid_current');
    }
} else {
    header('location: ../view/changePassword.php');
}
?>