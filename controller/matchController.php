<?php
session_start();
require_once('../model/matchModel.php');
require_once('../model/tournamentModel.php');

if (isset($_POST['schedule'])) {
    $t_id = $_POST['tournament_id'];
    $team1 = $_POST['team1_id'];
    $team2 = $_POST['team2_id'];
    $date = $_POST['match_date'];

    if ($team1 == $team2) {
        header("location: ../view/manageMatches.php?id=$t_id&error=same_team");
    } else {
        $match = [
            'tournament_id' => $t_id,
            'team1_id' => $team1,
            'team2_id' => $team2,
            'match_date' => $date
        ];
        if (scheduleMatch($match)) {
            logActivity("Match scheduled in Tournament ID: $t_id");
            header("location: ../view/detailsTournament.php?id=$t_id&success=scheduled");
        } else {
            header("location: ../view/manageMatches.php?id=$t_id&error=db_error");
        }
    }
}

if (isset($_POST['update_result'])) {
    $m_id = $_POST['match_id'];
    $t_id = $_POST['t_id'];
    $winner = $_POST['winner_id'];
    $status = $_POST['status'];

    if (updateMatchResult($m_id, $winner, $status)) {
        header("location: ../view/detailsTournament.php?id=$t_id&success=updated");
    } else {
        header("location: ../view/updateMatch.php?match_id=$m_id&error=db_error");
    }
}
?>