<?php
session_start();
require_once('../model/tournamentModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php');
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tournament = getTournamentById($id);
    if (deleteTournament($id)) {
        logActivity("Tournament Deleted: {$tournament['title']} (ID: $id)");
        header('location: ../view/tournamentList.php?success=deleted');
    } else {
        header('location: ../view/tournamentList.php?error=db_error');
    }
}
?>