<?php
require_once '../model/tournamentModel.php';
require_once '../model/matchModel.php';
require_once '../model/teamModel.php';
$user = require_login();

$totals = getSiteTotals();
$byCategory = countTournamentsByCategory();
$upcoming = getUpcomingMatches(5);
$results = getRecentResults(4);
$myTeams = getMyTeams($user['id']);
$myTeamIds = array_column($myTeams, 'id');
$spotlight = searchTournaments(['status' => 'Ongoing'], 3);
$maxCat = $byCategory ? max(array_column($byCategory, 'value')) : 1;
$isAdmin = $user['role'] === 'Admin';
$canHost = in_array($user['role'], ['Admin', 'Organizer'], true);

$active = 'home';
$pageTitle = 'Dashboard';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head">
        <div>
            <h1>Hello, <?= e(explode(' ', $user['name'])[0]) ?></h1>
            <p><?= date('l, F j') ?> · here's what's happening across your tournaments.</p>
        </div>
        <div class="row wrap">
            <?php if ($canHost): ?><a class="btn btn-primary" href="createTournament.php"><?= icon('plus') ?> New tournament</a><?php endif; ?>
            <?php if ($user['role'] === 'Player'): ?><a class="btn btn-primary" href="createTeam.php"><?= icon('plus') ?> Create team</a><?php endif; ?>
            <a class="btn btn-ghost" href="tournamentList.php">Browse all</a>
        </div>
    </div>

    <div class="grid grid-4">
        <?= stat_tile('trophy', 'indigo', $totals['active'], 'Active tournaments') ?>
        <?= stat_tile('users', 'green', $totals['teams'], 'Teams registered') ?>
        <?= stat_tile('target', 'amber', $totals['matches'], 'Matches scheduled') ?>
        <?= stat_tile($isAdmin ? 'zap' : 'award', 'pink', $isAdmin ? getTodayActivityCount() : count($myTeams), $isAdmin ? "Today's activity" : 'My teams') ?>
    </div>

    <div class="grid grid-main mt-3">
        <div class="stack">
            <div class="card">
                <div class="card-head"><h2><?= icon('flame') ?> Live now</h2><a class="small" href="tournamentList.php?status=Ongoing">See all →</a></div>
                <?php if ($spotlight): ?>
                    <div class="grid grid-2">
                        <?php foreach (array_slice($spotlight, 0, 2) as $t): echo tournament_card($t); endforeach; ?>
                    </div>
                <?php else: ?>
                    <?= empty_state('activity', 'Nothing live right now', 'Ongoing tournaments will show up here.') ?>
                <?php endif; ?>
            </div>

            <div class="card">
                <div class="card-head"><h2><?= icon('calendar') ?> Upcoming fixtures</h2><?php if (!$isAdmin): ?><a class="small" href="myMatches.php">My matches →</a><?php endif; ?></div>
                <?php foreach ($upcoming as $m): echo match_row($m, $myTeamIds); endforeach; ?>
                <?php if (!$upcoming): ?><?= empty_state('calendar', 'No fixtures scheduled', 'Organisers can schedule matches from a tournament page.') ?><?php endif; ?>
            </div>

            <?php if ($results): ?>
                <div class="card">
                    <div class="card-head"><h2><?= icon('check-circle') ?> Latest results</h2></div>
                    <?php foreach ($results as $m): echo match_row($m, $myTeamIds); endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <aside class="stack">
            <div class="card">
                <div class="card-head"><h3>Tournaments by sport</h3></div>
                <?php foreach ($byCategory as $row): $meta = category_meta($row['label']); ?>
                    <div class="hbar">
                        <span><?= icon($meta['icon']) ?> <?= e($row['label']) ?></span>
                        <div class="track"><div class="fill" style="width:<?= round($row['value'] / $maxCat * 100) ?>%;<?= banner_style($row['label']) ?>"></div></div>
                        <span class="val"><?= (int) $row['value'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card">
                <div class="card-head"><h3>Quick actions</h3></div>
                <div class="stack" style="--gap:10px">
                    <?php if ($canHost): ?><a class="btn btn-ghost btn-block" href="createTournament.php"><?= icon('trophy') ?> Host a tournament</a><?php endif; ?>
                    <a class="btn btn-ghost btn-block" href="createTeam.php"><?= icon('users') ?> Create a team</a>
                    <a class="btn btn-ghost btn-block" href="teamList.php"><?= icon('clipboard') ?> Browse teams</a>
                    <?php if ($isAdmin): ?>
                        <a class="btn btn-ghost btn-block" href="allUser.php"><?= icon('shield') ?> Manage users</a>
                        <a class="btn btn-ghost btn-block" href="systemReports.php"><?= icon('bar-chart') ?> System reports</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$isAdmin): ?>
                <div class="card">
                    <div class="card-head"><h3>My teams</h3></div>
                    <?php foreach (array_slice($myTeams, 0, 4) as $tm): ?>
                        <div class="list-item">
                            <?= crest($tm['name'], 38) ?>
                            <div><strong><?= e($tm['name']) ?></strong><div class="muted small"><?= e($tm['sport']) ?> · <?= (int) $tm['member_count'] ?> players</div></div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!$myTeams): ?><p class="muted small mb-0">You're not on a team yet. <a href="createTeam.php">Create one</a>.</p><?php endif; ?>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
