<?php
session_start();
require_once('../model/userModel.php');
require_once('../model/tournamentModel.php'); // For activity log

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $user = ['id' => $id, 'role' => $role, 'status' => $status];

    if (updateUserAdmin($user)) {
        // Log if success
        logActivity("Admin updated User ID: $id (Role: $role, Status: $status)");
        header('location: ../view/allUser.php?success=updated');
    } else {
        header("location: ../view/editUser.php?id=$id&error=db_error");
    }
}
?>