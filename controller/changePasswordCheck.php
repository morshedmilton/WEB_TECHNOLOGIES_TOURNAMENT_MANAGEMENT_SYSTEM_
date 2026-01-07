<?php
session_start();
require_once('../model/userModel.php');
if (isset($_POST['submit'])) {
    $current = $_POST['currentPassword'];
    $new = $_POST['newPassword'];
    $confirm = $_POST['confirmNewPassword'];
    $user = login($_SESSION['username'], $current);
    if ($user && $new === $confirm) {
        if (updatePassword($user['id'], $new)) {
            header('location: ../view/profile.php?success=password_changed');
        }
    } else {
        header('location: ../view/changePassword.php?error=mismatch');
    }
}
?>