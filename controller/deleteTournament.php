<?php
session_start();
require_once('../model/tournamentModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php');
}

if (isset($_GET['id'])) {
    if (deleteTournament($_GET['id'])) {
        header('location: ../view/tournamentList.php?success=deleted');
    } else {
        header('location: ../view/tournamentList.php?error=db_error');
    }
} else {
    header('location: ../view/tournamentList.php');
}
?>