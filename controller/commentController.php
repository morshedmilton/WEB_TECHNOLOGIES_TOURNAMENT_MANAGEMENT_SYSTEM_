<?php
session_start();
require_once('../model/commentModel.php');
require_once('../model/tournamentModel.php');

if (isset($_POST['postComment'])) {
    $t_id = $_POST['tournament_id'];
    $comment = trim($_POST['comment']);
    $rating = $_POST['rating'];
    $username = $_SESSION['username'];

    if ($comment == "") {
        header("location: ../view/detailsTournament.php?id=$t_id&error=empty_comment");
    } else {
        if (addComment($t_id, $username, $comment, $rating)) {
            logActivity("New comment posted by $username");
            header("location: ../view/detailsTournament.php?id=$t_id&success=commented");
        } else {
            header("location: ../view/detailsTournament.php?id=$t_id&error=db_error");
        }
    }
}
?>
