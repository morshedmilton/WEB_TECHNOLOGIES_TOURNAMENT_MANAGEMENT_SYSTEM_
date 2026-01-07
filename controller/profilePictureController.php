<?php
session_start();
require_once('../model/userModel.php');
if (isset($_POST['upload'])) {
    $file = $_FILES['profile_pic'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $user = getUserByUsername($_SESSION['username']);
    $newName = "user_" . $user['id'] . "_" . time() . "." . $ext;
    if (move_uploaded_file($file['tmp_name'], '../uploads/users/' . $newName)) {
        updateProfilePicture($user['id'], $newName);
        header('location: ../view/profile.php?success=uploaded');
    }
}
?>