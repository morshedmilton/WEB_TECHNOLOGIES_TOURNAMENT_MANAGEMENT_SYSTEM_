<?php
session_start();
require_once('../model/userModel.php');

if (isset($_POST['submit'])) {
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmNewPassword = trim($_POST['confirmNewPassword']);
    $username = $_SESSION['username'];

    // 1. Empty field check
    if ($currentPassword == "" || $newPassword == "" || $confirmNewPassword == "") {
        header('location: ../view/changePassword.php?error=null');
        exit();
    }

    // 2. New password matching check
    if ($newPassword !== $confirmNewPassword) {
        header('location: ../view/changePassword.php?error=mismatch');
        exit();
    }

    // 3. Check if current password is correct
    $user = login($username, $currentPassword);

    if ($user) {
        // 4. Update password
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