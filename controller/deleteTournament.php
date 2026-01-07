<?php
session_start();
require_once('../model/tournamentModel.php');

// কুকি চেক
if (!isset($_COOKIE['status'])) {
    header('location: ../view/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ডিলিট করার আগে টুর্নামেন্টের নাম জেনে নেওয়া (লগের জন্য)
    $tournament = getTournamentById($id);
    $title = $tournament['title'];

    if (deleteTournament($id)) {
        // অ্যাক্টিভিটি লগ করা
        logActivity("Tournament Deleted: $title (ID: $id) by {$_SESSION['username']}");
        header('location: ../view/tournamentList.php?success=deleted');
    } else {
        header('location: ../view/tournamentList.php?error=db_error');
    }
} else {
    header('location: ../view/tournamentList.php');
}
?>