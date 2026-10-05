<?php
require_once '../model/teamModel.php';
require_once '../model/tournamentModel.php';
require_once '../model/matchModel.php';
$user = require_login();
$myTeams = getMyTeams($user['id']);
$teamIds = array_column($myTeams, 'id');
$myTournaments = getTournamentsByTeamIDs($teamIds);
$myMatches = getMatchesByTeamIDs($teamIds);
$upcoming = array_filter($myMatches, function ($m) { return $m['status'] !== 'Finished'; });
$finished = array_filter($myMatches, function ($m) { return $m['status'] === 'Finished'; });
$wins = count(array_filter($finished, function ($m) use ($teamIds) { return in_array($m['winner_id'], $teamIds); }));
$active = 'matches';
$pageTitle = 'My matches';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head"><div><h1>My matches</h1><p>Your teams, tournaments and fixtures in one view.</p></div>
        <a class="btn btn-ghost" href="createTeam.php"><?= icon('plus') ?> Create team</a></div>

    <div class="grid grid-4">
        <?= stat_tile('users', 'indigo', count($myTeams), 'My teams') ?>
        <?= stat_tile('trophy', 'amber', count($myTournaments), 'Tournaments joined') ?>
        <?= stat_tile('calendar', 'blue', count($upcoming), 'Upcoming matches') ?>
        <?= stat_tile('award', 'green', $wins . '/' . count($finished), 'Wins') ?>
    </div>

    <div class="grid grid-main mt-3">
        <div class="stack">
            <div class="card">
                <div class="card-head"><h2>Upcoming &amp; live</h2></div>
                <?php foreach (array_reverse($upcoming) as $m): echo match_row($m, $teamIds); endforeach; ?>
                <?php if (!$upcoming): ?><?= empty_state('calendar', 'No upcoming matches', 'Join a tournament to see your fixtures here.') ?><?php endif; ?>
            </div>
            <div class="card">
                <div class="card-head"><h2>Results</h2></div>
                <?php foreach ($finished as $m): echo match_row($m, $teamIds); endforeach; ?>
                <?php if (!$finished): ?><p class="muted mb-0">No finished matches yet.</p><?php endif; ?>
            </div>
        </div>
        <aside class="stack">
            <div class="card">
                <div class="card-head"><h3>My tournaments</h3></div>
                <?php foreach ($myTournaments as $t): ?>
                    <a class="list-item" href="detailsTournament.php?id=<?= (int) $t['id'] ?>" style="color:inherit">
                        <span class="stat-icon tone-indigo" style="width:40px;height:40px;font-size:1.2rem"><?= icon(category_meta($t['category'])['icon'], 20) ?></span>
                        <div style="min-width:0"><strong><?= e($t['title']) ?></strong><div><?= status_badge($t['status']) ?></div></div>
                    </a>
                <?php endforeach; ?>
                <?php if (!$myTournaments): ?><p class="muted small mb-0">You haven't joined any tournaments yet. <a href="tournamentList.php">Browse tournaments</a>.</p><?php endif; ?>
            </div>
            <div class="card">
                <div class="card-head"><h3>My teams</h3></div>
                <?php foreach ($myTeams as $tm): ?>
                    <div class="list-item"><?= crest($tm['name'], 38) ?><div><strong><?= e($tm['name']) ?></strong><div class="muted small"><?= e($tm['sport']) ?></div></div></div>
                <?php endforeach; ?>
                <?php if (!$myTeams): ?><p class="muted small mb-0">No teams yet.</p><?php endif; ?>
            </div>
        </aside>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
