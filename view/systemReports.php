<?php
require_once '../model/tournamentModel.php';
require_once '../model/userModel.php';
require_once '../model/activityModel.php';
$user = require_role('Admin');

$totals = getSiteTotals();
$byCategory = countTournamentsByCategory();
$byStatus = countTournamentsByStatus();
$roles = countUsersByRole();
$daily = getActivityByDay(7);
$top = getTopRatedTournaments(5);
$messages = getContactMessages();
$maxCat = $byCategory ? max(array_column($byCategory, 'value')) : 1;
$maxDay = max(1, max(array_column($daily, 'value')));

// Conic-gradient donut for tournament status.
$palette = ['Upcoming' => '#3b82f6', 'Ongoing' => '#ef4444', 'Completed' => '#94a3b8'];
$sum = max(1, array_sum($byStatus));
$stops = [];
$acc = 0;
foreach ($byStatus as $label => $n) {
    $start = $acc / $sum * 100;
    $acc += $n;
    $stops[] = $palette[$label] . ' ' . round($start, 2) . '% ' . round($acc / $sum * 100, 2) . '%';
}

$active = 'reports';
$pageTitle = 'System reports';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head"><div><h1>System reports</h1><p>Platform health at a glance · generated <?= date('M j, Y g:i A') ?></p></div>
        <button class="btn btn-ghost" onclick="window.print()"><?= icon('printer') ?> Print report</button></div>

    <div class="grid grid-4">
        <?= stat_tile('trophy', 'indigo', $totals['tournaments'], 'Total tournaments') ?>
        <?= stat_tile('user', 'green', array_sum($roles), 'Registered users') ?>
        <?= stat_tile('users', 'amber', $totals['teams'], 'Teams') ?>
        <?= stat_tile('check-circle', 'blue', $totals['finished'] . '/' . $totals['matches'], 'Matches completed') ?>
    </div>

    <div class="grid grid-3 mt-3">
        <div class="card">
            <div class="card-head"><h3>Tournaments by status</h3></div>
            <div class="donut-wrap">
                <div class="donut" style="background:conic-gradient(<?= implode(',', $stops) ?>)"><div class="donut-center"><div><?= (int) $totals['tournaments'] ?><small>total</small></div></div></div>
                <div class="legend"><?php foreach ($byStatus as $label => $n): ?><div><i style="background:<?= $palette[$label] ?>"></i><?= e($label) ?> · <strong><?= $n ?></strong></div><?php endforeach; ?></div>
            </div>
        </div>
        <div class="card">
            <div class="card-head"><h3>Users by role</h3></div>
            <?php $rc = ['Admin' => '#ef4444', 'Organizer' => '#f59e0b', 'Player' => '#6366f1']; $rmax = max(1, max($roles)); foreach ($roles as $label => $n): ?>
                <div class="hbar"><span><?= e($label) ?></span><div class="track"><div class="fill" style="width:<?= round($n / $rmax * 100) ?>%;background:<?= $rc[$label] ?>"></div></div><span class="val"><?= $n ?></span></div>
            <?php endforeach; ?>
        </div>
        <div class="card">
            <div class="card-head"><h3>Tournaments by sport</h3></div>
            <?php foreach ($byCategory as $row): ?>
                <div class="hbar"><span><?= icon(category_meta($row['label'])['icon']) ?> <?= e($row['label']) ?></span><div class="track"><div class="fill" style="width:<?= round($row['value'] / $maxCat * 100) ?>%;<?= banner_style($row['label']) ?>"></div></div><span class="val"><?= (int) $row['value'] ?></span></div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid grid-2 mt-3">
        <div class="card">
            <div class="card-head"><h3>Platform activity · last 7 days</h3></div>
            <div class="vbars">
                <?php foreach ($daily as $d): ?>
                    <div class="col"><b><?= (int) $d['value'] ?></b><i style="height:<?= max(3, round($d['value'] / $maxDay * 100)) ?>%"></i><span><?= e($d['label']) ?></span></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-head"><h3>Top-rated tournaments</h3></div>
            <?php foreach ($top as $i => $t): ?>
                <a class="list-item" href="detailsTournament.php?id=<?= (int) $t['id'] ?>" style="color:inherit">
                    <strong class="muted" style="width:20px">#<?= $i + 1 ?></strong>
                    <div style="flex:1;min-width:0"><strong><?= e($t['title']) ?></strong><div class="muted small"><?= icon(category_meta($t['category'])['icon']) ?> <?= e($t['category']) ?> · <?= (int) $t['reviews'] ?> reviews</div></div>
                    <div><?= stars_html($t['avg_rating']) ?> <strong><?= number_format((float) $t['avg_rating'], 1) ?></strong></div>
                </a>
            <?php endforeach; ?>
            <?php if (!$top): ?><p class="muted mb-0">No reviews yet.</p><?php endif; ?>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-head"><h3>Support inbox</h3><span class="muted small"><?= count($messages) ?> message<?= count($messages) === 1 ? '' : 's' ?></span></div>
        <?php foreach ($messages as $m): ?>
            <div class="review">
                <?= avatar_html($m['name'], null, 40) ?>
                <div><div><strong><?= e($m['subject']) ?></strong> <span class="muted small">· <?= e($m['name']) ?> &lt;<?= e($m['email']) ?>&gt; · <?= e(time_ago($m['created_at'])) ?></span></div>
                    <p class="mb-0 muted"><?= e($m['message']) ?></p></div>
            </div>
        <?php endforeach; ?>
        <?php if (!$messages): ?><p class="muted mb-0">Inbox is empty.</p><?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
