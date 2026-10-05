<?php
require_once '../model/tournamentModel.php';
$user = require_role(['Admin', 'Organizer'], '../view/');
require_post('tournamentList.php');

function read_tournament_input()
{
    return [
        'title' => trim($_POST['title'] ?? ''),
        'category' => in_array($_POST['category'] ?? '', CATEGORIES, true) ? $_POST['category'] : CATEGORIES[0],
        'description' => trim($_POST['description'] ?? ''),
        'status' => in_array($_POST['status'] ?? '', TOURNAMENT_STATUSES, true) ? $_POST['status'] : 'Upcoming',
        'location' => trim($_POST['location'] ?? ''),
        'start_date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['start_date'] ?? '') ? $_POST['start_date'] : null,
        'prize_pool' => trim($_POST['prize_pool'] ?? ''),
        'max_teams' => max(2, min(64, (int) ($_POST['max_teams'] ?? 8))),
    ];
}

if (isset($_POST['submit'])) {
    $t = read_tournament_input();
    if ($t['title'] === '' || $t['description'] === '') {
        flash('error', 'Title and description are required.');
        redirect('../view/createTournament.php');
    }

    $err = null;
    $banner = save_upload($_FILES['banner'] ?? [], __DIR__ . '/../uploads/banners', 'image', 'banner', $err);
    if ($err) {
        flash('error', $err);
        redirect('../view/createTournament.php');
    }
    $t['banner_image'] = $banner;
    $t['created_by'] = $user['id'];
    $id = createTournament($t);

    $docErr = null;
    $doc = save_upload($_FILES['rulebook'] ?? [], __DIR__ . '/../uploads/docs', 'document', 'rule', $docErr);
    if ($doc) {
        addAttachment($id, $_FILES['rulebook']['name'], $doc, strtolower(pathinfo($doc, PATHINFO_EXTENSION)));
    }

    logActivity('Tournament created: ' . $t['title']);
    flash('success', 'Tournament created.' . ($docErr ? ' (Rulebook was skipped: ' . $docErr . ')' : ''));
    redirect('../view/detailsTournament.php?id=' . $id);
}

if (isset($_POST['update'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $existing = getTournamentById($id);
    if (!$existing || !can_manage_tournament($existing)) {
        flash('error', 'You can only edit tournaments you organise.');
        redirect('../view/tournamentList.php');
    }
    $t = read_tournament_input();
    if ($t['title'] === '' || $t['description'] === '') {
        flash('error', 'Title and description are required.');
        redirect('../view/editTournament.php?id=' . $id);
    }
    updateTournament($id, $t);
    logActivity('Tournament updated: ' . $t['title']);
    flash('success', 'Changes saved.');
    redirect('../view/detailsTournament.php?id=' . $id);
}

redirect('../view/tournamentList.php');
