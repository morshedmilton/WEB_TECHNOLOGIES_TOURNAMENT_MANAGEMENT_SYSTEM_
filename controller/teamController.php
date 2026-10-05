<?php
require_once '../model/teamModel.php';
require_once '../model/tournamentModel.php';
$user = require_login('../view/');
require_post('teamList.php');

if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $sport = in_array($_POST['sport'] ?? '', CATEGORIES, true) ? $_POST['sport'] : CATEGORIES[0];
    $raw = trim($_POST['members'] ?? '');
    $_SESSION['old'] = ['name' => $name, 'sport' => $sport, 'members' => $raw];

    $usernames = array_values(array_filter(array_map('trim', explode(',', $raw)), 'strlen'));
    list($ids, $unknown) = resolvePlayers($usernames);

    $error = null;
    if ($name === '') {
        $error = 'Team name cannot be empty.';
    } elseif (teamNameExists($name)) {
        $error = 'A team with that name already exists.';
    } elseif ($unknown) {
        $error = 'Not registered players: ' . implode(', ', $unknown) . '.';
    }
    if ($error) {
        flash('error', $error);
        redirect('../view/createTeam.php');
    }

    unset($_SESSION['old']);
    createTeam($name, $sport, $user['id'], $ids);
    logActivity("New team formed: $name");
    flash('success', "Team \"$name\" created.");
    redirect('../view/teamList.php');
}

$tid = (int) ($_POST['tournament_id'] ?? 0);
$teamId = (int) ($_POST['team_id'] ?? 0);
$back = '../view/detailsTournament.php?id=' . $tid;
$t = getTournamentById($tid);
$team = getTeamById($teamId);
if (!$t || !$team) {
    redirect('../view/tournamentList.php');
}
if ((int) $team['created_by'] !== (int) $user['id'] && $user['role'] !== 'Admin') {
    flash('error', 'Only the team captain can do that.');
    redirect($back);
}

if (isset($_POST['join'])) {
    if ($t['status'] === 'Completed') {
        flash('error', 'Registration is closed for completed tournaments.');
    } elseif ($team['sport'] !== $t['category']) {
        flash('error', "This is a {$t['category']} tournament; {$team['name']} plays {$team['sport']}.");
    } elseif (isTeamRegistered($tid, $teamId)) {
        flash('error', 'That team is already registered.');
    } elseif ((int) $t['team_count'] >= (int) $t['max_teams']) {
        flash('error', 'This tournament is full.');
    } else {
        joinTournament($tid, $teamId);
        logActivity("Team {$team['name']} joined {$t['title']}");
        flash('success', "{$team['name']} is in! Good luck.");
    }
}

if (isset($_POST['leave'])) {
    leaveTournament($tid, $teamId);
    logActivity("Team {$team['name']} withdrew from {$t['title']}");
    flash('success', "{$team['name']} withdrew from the tournament.");
}
redirect($back);
