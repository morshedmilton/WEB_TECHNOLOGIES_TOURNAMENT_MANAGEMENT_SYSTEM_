<?php
require_once '../model/tournamentModel.php';
require_once '../model/teamModel.php';
$user = require_login();
$id = (int) ($_GET['id'] ?? 0);
$t = getTournamentById($id);
if (!$t || !can_manage_tournament($t)) {
    flash('error', 'Only the organiser can schedule matches.');
    redirect('tournamentList.php');
}
$teams = getRegisteredTeams($id);
$active = 'tournaments';
$pageTitle = 'Schedule match';
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="tournamentList.php">Tournaments</a> / <a href="detailsTournament.php?id=<?= $id ?>#matches"><?= e($t['title']) ?></a> / Schedule</div>
    <div class="page-head"><div><h1>Schedule a match</h1><p><?= e($t['title']) ?></p></div></div>
    <div class="card" style="max-width:620px">
        <?php if (count($teams) < 2): ?>
            <?= empty_state('users', 'Not enough teams', 'At least two teams must be registered before you can schedule a match.') ?>
        <?php else: ?>
            <form method="post" action="../controller/matchController.php">
                <?= csrf_field() ?><input type="hidden" name="tournament_id" value="<?= $id ?>">
                <div class="form-grid">
                    <div class="field"><label for="team1_id">Team 1</label>
                        <select class="input" name="team1_id" id="team1_id"><?php foreach ($teams as $tm): ?><option value="<?= (int) $tm['id'] ?>"><?= e($tm['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="field"><label for="team2_id">Team 2</label>
                        <select class="input" name="team2_id" id="team2_id"><?php foreach ($teams as $i => $tm): ?><option value="<?= (int) $tm['id'] ?>" <?= $i === 1 ? 'selected' : '' ?>><?= e($tm['name']) ?></option><?php endforeach; ?></select></div>
                </div>
                <div class="field"><label for="match_date">Date &amp; time</label>
                    <input class="input" type="datetime-local" name="match_date" id="match_date" required></div>
                <button class="btn btn-primary btn-lg" type="submit" name="schedule" value="1">Schedule match</button>
            </form>
        <?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
