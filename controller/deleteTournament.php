<?php
require_once '../model/tournamentModel.php';
$user = require_login('../view/');
require_post('tournamentList.php');

$id = (int) ($_POST['id'] ?? 0);
$t = getTournamentById($id);
if (!$t || !can_manage_tournament($t)) {
    flash('error', 'You can only delete tournaments you organise.');
    redirect('../view/tournamentList.php');
}
deleteTournament($id);
logActivity('Tournament deleted: ' . $t['title']);
flash('success', 'Tournament deleted.');
redirect('../view/tournamentList.php');
