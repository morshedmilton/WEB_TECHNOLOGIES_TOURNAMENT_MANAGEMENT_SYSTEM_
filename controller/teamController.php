<?php
session_start();
require_once('../model/teamModel.php');
require_once('../model/tournamentModel.php');

// 1. Team creation request with player validation
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $members_raw = trim($_POST['members']);
    $members_array = explode(',', $members_raw); // Separate by comma

    $valid_team = true;
    foreach ($members_array as $m) {
        $m = trim($m);
        if (!isPlayerRegistered($m)) {
            $valid_team = false;
            break;
        }
    }

    if ($name == "") {
        header('location: ../view/createTeam.php?error=null');
    } elseif (!$valid_team) {
        // If any member is not registered as a player
        header('location: ../view/createTeam.php?error=invalid_members');
    } else {
        $team = ['name' => $name, 'members' => $members_raw, 'created_by' => $_SESSION['username']];
        if (createTeam($team)) {
            logActivity("New team formed: $name");
            header('location: ../view/teamList.php?success=created');
        } else {
            header('location: ../view/createTeam.php?error=db_error');
        }
    }
}

// 2. Request to join tournament (as before)
if (isset($_POST['join'])) {
    $t_id = $_POST['tournament_id'];
    $team_id = $_POST['team_id'];

    if (joinTournament($t_id, $team_id)) {
        header("location: ../view/detailsTournament.php?id=$t_id&success=joined");
    } else {
        header("location: ../view/detailsTournament.php?id=$t_id&error=db_error");
    }
}
?>