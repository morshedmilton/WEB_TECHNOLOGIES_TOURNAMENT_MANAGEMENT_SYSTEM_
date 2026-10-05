<?php
require_once '../model/tournamentModel.php';
$user = require_login();
$t = getTournamentById((int) ($_GET['id'] ?? 0));
if (!$t) {
    flash('error', 'Tournament not found.');
    redirect('tournamentList.php');
}
if (!can_manage_tournament($t)) {
    flash('error', 'You can only edit tournaments you organise.');
    redirect('detailsTournament.php?id=' . (int) $t['id']);
}
$mode = 'edit';
$active = 'tournaments';
$pageTitle = 'Edit ' . $t['title'];
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="tournamentList.php">Tournaments</a> / <a href="detailsTournament.php?id=<?= (int) $t['id'] ?>"><?= e($t['title']) ?></a> / Edit</div>
    <div class="page-head"><div><h1>Edit tournament</h1></div></div>
    <div class="card" style="max-width:820px">
        <?php include 'partials/tournamentForm.php'; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
