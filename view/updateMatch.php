<?php
require_once '../model/matchModel.php';
require_once '../model/tournamentModel.php';
$user = require_login();
$m = getMatchById((int) ($_GET['match_id'] ?? 0));
$t = $m ? getTournamentById($m['tournament_id']) : null;
if (!$m || !can_manage_tournament($t)) {
    flash('error', 'Only the organiser can update results.');
    redirect('tournamentList.php');
}
$active = 'tournaments';
$pageTitle = 'Update result';
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="detailsTournament.php?id=<?= (int) $t['id'] ?>#matches"><?= e($t['title']) ?></a> / Update result</div>
    <div class="page-head"><div><h1>Update match result</h1></div></div>
    <div class="card" style="max-width:620px">
        <form method="post" action="../controller/matchController.php">
            <?= csrf_field() ?><input type="hidden" name="match_id" value="<?= (int) $m['id'] ?>"><input type="hidden" name="t_id" value="<?= (int) $t['id'] ?>">
            <div class="match" style="margin-bottom:22px">
                <div class="team"><?= crest($m['team1_name']) ?><span><?= e($m['team1_name']) ?></span></div>
                <div class="center-col"><div class="score muted" style="font-size:1rem">VS</div><div class="when"><?= e(fmt_date($m['match_date'], 'M j · g:i A')) ?></div></div>
                <div class="team right"><span><?= e($m['team2_name']) ?></span><?= crest($m['team2_name']) ?></div>
            </div>
            <div class="form-grid">
                <div class="field"><label for="score1"><?= e($m['team1_name']) ?> score</label>
                    <input class="input" type="number" min="0" max="999" name="score1" id="score1" value="<?= (int) $m['team1_score'] ?>" required></div>
                <div class="field"><label for="score2"><?= e($m['team2_name']) ?> score</label>
                    <input class="input" type="number" min="0" max="999" name="score2" id="score2" value="<?= (int) $m['team2_score'] ?>" required></div>
            </div>
            <div class="field"><label for="status">Match status</label>
                <select class="input" name="status" id="status"><?php foreach (MATCH_STATUSES as $s): ?><option <?= $m['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
                <div class="hint">The winner is determined from the score when the match is marked Finished.</div></div>
            <div class="row">
                <button class="btn btn-primary btn-lg" type="submit" name="update_result" value="1">Save result</button>
                <a class="btn btn-ghost btn-lg" href="detailsTournament.php?id=<?= (int) $t['id'] ?>#matches">Cancel</a>
            </div>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
