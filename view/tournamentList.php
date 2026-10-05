<?php
require_once '../model/tournamentModel.php';
$user = require_login();

$filters = [
    'q' => trim($_GET['q'] ?? ''),
    'category' => $_GET['category'] ?? '',
    'status' => $_GET['status'] ?? '',
];
$tournaments = searchTournaments($filters);

// AJAX live search asks for just the results grid.
function render_results($tournaments)
{
    if (!$tournaments) {
        return empty_state('search', 'No tournaments found', 'Try a different search or clear the filters.');
    }
    $html = '<div class="grid grid-3">';
    foreach ($tournaments as $t) {
        $html .= tournament_card($t);
    }
    return $html . '</div>';
}

require_once 'partials/components.php';
if (isset($_GET['partial'])) {
    echo '<p class="muted small" style="margin-bottom:14px">' . count($tournaments) . ' tournament' . (count($tournaments) === 1 ? '' : 's') . '</p>';
    echo render_results($tournaments);
    exit();
}

$canHost = in_array($user['role'], ['Admin', 'Organizer'], true);
$active = 'tournaments';
$pageTitle = 'Tournaments';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head">
        <div>
            <h1>Tournaments</h1>
            <p>Discover, join and follow competitions across every sport.</p>
        </div>
        <?php if ($canHost): ?><a class="btn btn-primary" href="createTournament.php"><?= icon('plus') ?> New tournament</a><?php endif; ?>
    </div>

    <div class="card" style="margin-bottom:24px">
        <div class="row wrap" style="gap:16px">
            <div class="searchbar">
                <input class="input" type="search" id="liveSearch" placeholder="Search by name, sport or location…" value="<?= e($filters['q']) ?>"
                    data-category="<?= e($filters['category']) ?>" data-status="<?= e($filters['status']) ?>" autocomplete="off">
            </div>
            <div class="chips">
                <?php foreach (['' => 'All status', 'Ongoing' => 'Live', 'Upcoming' => 'Upcoming', 'Completed' => 'Completed'] as $val => $label): ?>
                    <a href="?status=<?= e($val) ?>" class="chip <?= $filters['status'] === $val ? 'active' : '' ?>" data-filter="status" data-value="<?= e($val) ?>"><?= $label ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="chips mt-2">
            <a href="?category=" class="chip <?= $filters['category'] === '' ? 'active' : '' ?>" data-filter="category" data-value="">All sports</a>
            <?php foreach (CATEGORIES as $c): ?>
                <a href="?category=<?= urlencode($c) ?>" class="chip <?= $filters['category'] === $c ? 'active' : '' ?>" data-filter="category" data-value="<?= e($c) ?>"><?= icon(category_meta($c)['icon']) ?> <?= e($c) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="results">
        <p class="muted small" style="margin-bottom:14px"><?= count($tournaments) ?> tournament<?= count($tournaments) === 1 ? '' : 's' ?></p>
        <?= render_results($tournaments) ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
