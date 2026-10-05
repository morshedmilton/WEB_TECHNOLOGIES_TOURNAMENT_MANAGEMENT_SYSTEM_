<?php
require_once '../model/matchModel.php';
require_once '../model/teamModel.php';
require_once '../model/tournamentModel.php';
$user = require_login('../view/');
require_post('tournamentList.php');

$tid = (int) ($_POST['tournament_id'] ?? $_POST['t_id'] ?? 0);
$t = getTournamentById($tid);
if (!$t || !can_manage_tournament($t)) {
    flash('error', 'Only the organiser can manage matches.');
    redirect('../view/tournamentList.php');
}
$back = '../view/detailsTournament.php?id=' . $tid . '#matches';

if (isset($_POST['schedule'])) {
    $t1 = (int) ($_POST['team1_id'] ?? 0);
    $t2 = (int) ($_POST['team2_id'] ?? 0);
    $date = str_replace('T', ' ', $_POST['match_date'] ?? '');
    $registered = array_column(getRegisteredTeams($tid), 'id');
    if ($t1 === $t2) {
        flash('error', 'Team 1 and Team 2 cannot be the same.');
        redirect('../view/manageMatches.php?id=' . $tid);
    }
    if (!in_array($t1, $registered) || !in_array($t2, $registered)) {
        flash('error', 'Both teams must be registered in this tournament.');
        redirect('../view/manageMatches.php?id=' . $tid);
    }
    if (!strtotime($date)) {
        flash('error', 'Please choose a valid date and time.');
        redirect('../view/manageMatches.php?id=' . $tid);
    }
    scheduleMatch($tid, $t1, $t2, date('Y-m-d H:i:s', strtotime($date)));
    logActivity('Match scheduled in ' . $t['title']);
    flash('success', 'Match scheduled.');
    redirect($back);
}

if (isset($_POST['update_result'])) {
    $mid = (int) ($_POST['match_id'] ?? 0);
    $m = getMatchById($mid);
    if (!$m || (int) $m['tournament_id'] !== $tid) {
        redirect($back);
    }
    $status = in_array($_POST['status'] ?? '', MATCH_STATUSES, true) ? $_POST['status'] : 'Scheduled';
    updateMatchResult($mid, max(0, (int) ($_POST['score1'] ?? 0)), max(0, (int) ($_POST['score2'] ?? 0)), $status);
    logActivity('Result updated: ' . $t['title']);
    flash('success', 'Result saved.');
    redirect($back);
}

if (isset($_POST['delete_match'])) {
    $mid = (int) ($_POST['match_id'] ?? 0);
    $m = getMatchById($mid);
    if ($m && (int) $m['tournament_id'] === $tid) {
        deleteMatch($mid);
        flash('success', 'Match deleted.');
    }
}
redirect($back);
