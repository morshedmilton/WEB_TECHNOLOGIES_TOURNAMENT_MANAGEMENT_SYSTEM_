<?php
session_start();
require_once('../model/tournamentModel.php');

// Cookie check
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get tournament name before deleting (for logging)
    $tournament = getTournamentById($id);
    $title = $tournament['title'];

    if (deleteTournament($id)) {
        // Log activity
        logActivity("Tournament Deleted: $title (ID: $id) by {$_SESSION['username']}");
        header('location: ../view/tournamentList.php?success=deleted');
    } else {
        header('location: ../view/tournamentList.php?error=db_error');
    }
} else {
    header('location: ../view/tournamentList.php');
}
?>