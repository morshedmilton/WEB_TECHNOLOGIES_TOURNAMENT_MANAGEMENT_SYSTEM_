<?php
require_once '../model/teamModel.php';
$user = require_login();
$sport = $_GET['sport'] ?? '';
$teams = getAllTeams($sport);
$members = getMembersForTeams(array_column($teams, 'id'));
$active = 'teams';
$pageTitle = 'Teams';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head">
        <div><h1>Teams</h1><p>Every squad registered on the platform.</p></div>
        <?php if ($user['role'] === 'Player'): ?><a class="btn btn-primary" href="createTeam.php"><?= icon('plus') ?> Create team</a><?php endif; ?>
    </div>
    <div class="chips" style="margin-bottom:24px">
        <a class="chip <?= $sport === '' ? 'active' : '' ?>" href="teamList.php">All sports</a>
        <?php foreach (CATEGORIES as $c): ?>
            <a class="chip <?= $sport === $c ? 'active' : '' ?>" href="?sport=<?= urlencode($c) ?>"><?= icon(category_meta($c)['icon']) ?> <?= e($c) ?></a>
        <?php endforeach; ?>
    </div>
    <?php if ($teams): ?>
        <div class="grid grid-3">
            <?php foreach ($teams as $tm): $meta = category_meta($tm['sport']); ?>
                <div class="card">
                    <div class="row" style="margin-bottom:14px">
                        <?= crest($tm['name'], 48) ?>
                        <div style="min-width:0"><h3 class="mb-0"><?= e($tm['name']) ?></h3><span class="muted small"><?= icon($meta['icon']) ?> <?= e($tm['sport']) ?> · <?= (int) $tm['member_count'] ?> players</span></div>
                    </div>
                    <div class="pill-list">
                        <?php foreach (array_slice($members[$tm['id']] ?? [], 0, 5) as $mem): ?>
                            <span class="pill"><?= avatar_html($mem['name'], $mem['profile_picture'], 24) ?><?= e(explode(' ', $mem['name'])[0]) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <p class="muted small mt-2 mb-0">Captain: <strong><?= e($tm['creator_name'] ?: '—') ?></strong></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card"><?= empty_state('users', 'No teams yet', 'Create the first one.') ?></div>
    <?php endif; ?>
</main>
<?php include 'partials/footer.php'; ?>
