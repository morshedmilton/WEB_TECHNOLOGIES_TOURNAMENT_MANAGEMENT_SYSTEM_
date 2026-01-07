<?php
session_start();
require_once('../model/teamModel.php');
require_once('../model/tournamentModel.php');
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $members_raw = trim($_POST['members']);
    $members_array = explode(',', $members_raw);
    $valid_team = true;
    foreach ($members_array as $m) {
        if (!isPlayerRegistered(trim($m))) {
            $valid_team = false;
            break;
        }
    }
    if ($name == "") {
        header('location: ../view/createTeam.php?error=null');
    } elseif (!$valid_team) {
        header('location: ../view/createTeam.php?error=invalid_members');
    } else {
        $team = ['name' => $name, 'members' => $members_raw, 'created_by' => $_SESSION['username']];
        if (createTeam($team)) {
            logActivity("New team formed: $name");
            header('location: ../view/teamList.php?success=created');
        }
    }
}
if (isset($_POST['join'])) {
    if (joinTournament($_POST['tournament_id'], $_POST['team_id'])) {
        header("location: ../view/detailsTournament.php?id={$_POST['tournament_id']}&success=joined");
    }
}
?>