<?php
session_start();
require_once('../model/tournamentModel.php');

if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = trim($_POST['content']);

    if ($title == "" || $category == "") {
        header('location: ../view/createTournament.php?error=null');
    } else {
        $tournament = [
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'created_by' => $_SESSION['username']
        ];
        if (createTournament($tournament)) {
            logActivity("Tournament created: $title");
            header('location: ../view/tournamentList.php?success=created');
        } else {
            header('location: ../view/createTournament.php?error=db_error');
        }
    }
}

// Update Logic
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $status = $_POST['status'];
    $description = trim($_POST['description']);

    if ($title == "") {
        header("location: ../view/editTournament.php?id={$id}&error=null");
    } else {
        $tournament = [
            'id' => $id,
            'title' => $title,
            'category' => $category,
            'status' => $status,
            'description' => $description
        ];

        if (updateTournament($tournament)) {
            header('location: ../view/tournamentList.php?success=updated');
        } else {
            header("location: ../view/editTournament.php?id={$id}&error=db_error");
        }
    }
}
?>