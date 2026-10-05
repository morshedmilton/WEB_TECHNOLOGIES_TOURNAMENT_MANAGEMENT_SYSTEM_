<?php
require_once '../model/tournamentModel.php';
require_once '../model/teamModel.php';
require_once '../model/matchModel.php';
require_once '../model/commentModel.php';
$user = require_login();

$id = (int) ($_GET['id'] ?? 0);
$t = getTournamentById($id);
if (!$t) {
    flash('error', 'Tournament not found.');
    redirect('tournamentList.php');
}

$registered = getRegisteredTeams($id);
$matches = getMatchesByTournament($id);
$standings = computeStandings($registered, $matches);
$comments = getCommentsByTournament($id);
$breakdown = getRatingBreakdown($id);
$attachments = getAttachmentsByTournament($id);
$members = getMembersForTeams(array_column($registered, 'id'));

$canManage = can_manage_tournament($t);
$registeredIds = array_column($registered, 'id');
$eligible = [];
foreach (getTeamsByCreator($user['id']) as $tm) {
    if ($tm['sport'] === $t['category'] && !in_array($tm['id'], $registeredIds)) {
        $eligible[] = $tm;
    }
}
$myRegistered = array_filter($registered, function ($tm) use ($user) {
    return (int) $tm['created_by'] === (int) $user['id'];
});
$isFull = count($registered) >= (int) $t['max_teams'];
$canJoin = $t['status'] !== 'Completed' && !$isFull && in_array($user['role'], ['Player', 'Organizer', 'Admin'], true);
$alreadyReviewed = hasUserReviewed($id, $user['id']);
$totalReviews = array_sum($breakdown);
$myTeamIds = array_column(getMyTeams($user['id']), 'id');

$active = 'tournaments';
$pageTitle = $t['title'];
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="tournamentList.php">Tournaments</a> / <?= e($t['title']) ?></div>

    <?= tournament_banner($t, true) ?>

    <div class="page-head mt-3">
        <div>
            <h1><?= e($t['title']) ?></h1>
            <p>Organised by <strong><?= e($t['organizer_name'] ?: 'Unknown') ?></strong>
                <?php if ($t['review_count'] > 0): ?> · <?= stars_html($t['avg_rating']) ?> <?= number_format((float) $t['avg_rating'], 1) ?> (<?= (int) $t['review_count'] ?>)<?php endif; ?></p>
        </div>
        <?php if ($canManage): ?>
            <div class="row wrap">
                <a class="btn btn-ghost" href="editTournament.php?id=<?= $id ?>"><?= icon('edit') ?> Edit</a>
                <form method="post" action="../controller/deleteTournament.php" data-confirm="This permanently deletes the tournament, its matches, registrations and reviews.">
                    <?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
                    <button class="btn btn-danger-ghost" type="submit"><?= icon('trash') ?> Delete</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-main">
        <div>
            <div class="tabs">
                <button class="tab active" data-tab="overview">Overview</button>
                <button class="tab" data-tab="standings">Teams &amp; standings</button>
                <button class="tab" data-tab="matches">Matches (<?= count($matches) ?>)</button>
                <button class="tab" data-tab="reviews">Reviews (<?= $totalReviews ?>)</button>
            </div>

            <!-- Overview -->
            <div class="tab-panel active" id="overview">
                <div class="card">
                    <h2>About this tournament</h2>
                    <p style="white-space:pre-line"><?= e($t['description']) ?></p>
                    <div class="info-grid mt-2">
                        <div><div class="k">Sport</div><div class="v"><?= icon(category_meta($t['category'])['icon']) ?> <?= e($t['category']) ?></div></div>
                        <div><div class="k">Status</div><div class="v"><?= status_badge($t['status']) ?></div></div>
                        <div><div class="k">Venue</div><div class="v"><?= e($t['location'] ?: 'To be announced') ?></div></div>
                        <div><div class="k">Start date</div><div class="v"><?= $t['start_date'] ? e(fmt_date($t['start_date'], 'D, M j, Y')) : 'TBA' ?></div></div>
                        <div><div class="k">Prize pool</div><div class="v"><?= e($t['prize_pool'] ?: '—') ?></div></div>
                        <div><div class="k">Capacity</div><div class="v"><?= count($registered) ?> / <?= (int) $t['max_teams'] ?> teams</div></div>
                    </div>
                </div>
            </div>

            <!-- Standings -->
            <div class="tab-panel" id="standings">
                <div class="card card-flush">
                    <div class="card-head" style="padding:22px 24px 0"><h2>League table</h2><span class="muted small">Win 3 · Draw 1 · Loss 0</span></div>
                    <?php if ($standings): ?>
                        <div class="table-wrap" style="margin-top:14px">
                            <table class="table">
                                <thead><tr><th>#</th><th>Team</th><th class="num">P</th><th class="num">W</th><th class="num">D</th><th class="num">L</th><th class="num">+/−</th><th class="num">Pts</th></tr></thead>
                                <tbody>
                                <?php foreach ($standings as $i => $row): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><div class="row"><?= crest($row['name'], 30) ?><strong><?= e($row['name']) ?></strong><?= in_array($row['id'], $myTeamIds) ? '<span class="badge badge-info">yours</span>' : '' ?></div></td>
                                        <td class="num"><?= $row['p'] ?></td><td class="num"><?= $row['w'] ?></td><td class="num"><?= $row['d'] ?></td><td class="num"><?= $row['l'] ?></td>
                                        <td class="num"><?= $row['for'] - $row['against'] > 0 ? '+' : '' ?><?= $row['for'] - $row['against'] ?></td>
                                        <td class="num pts"><?= $row['pts'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <?= empty_state('users', 'No teams yet', 'Be the first to register a team.') ?>
                    <?php endif; ?>
                </div>
                <?php if ($registered): ?>
                    <div class="card mt-3">
                        <h2>Rosters</h2>
                        <div class="grid grid-2">
                            <?php foreach ($registered as $tm): ?>
                                <div>
                                    <div class="row" style="margin-bottom:8px"><?= crest($tm['name']) ?><strong><?= e($tm['name']) ?></strong></div>
                                    <div class="pill-list">
                                        <?php foreach ($members[$tm['id']] ?? [] as $mem): ?>
                                            <span class="pill"><?= avatar_html($mem['name'], $mem['profile_picture'], 24) ?><?= e($mem['name']) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Matches -->
            <div class="tab-panel" id="matches">
                <div class="card">
                    <div class="card-head">
                        <h2>Fixtures &amp; results</h2>
                        <?php if ($canManage && count($registered) >= 2): ?><a class="btn btn-primary btn-sm" href="manageMatches.php?id=<?= $id ?>"><?= icon('plus') ?> Schedule match</a><?php endif; ?>
                    </div>
                    <?php foreach ($matches as $m): ?>
                        <?= match_row($m, $myTeamIds) ?>
                        <?php if ($canManage): ?>
                            <div class="table-actions" style="margin-top:6px">
                                <a class="btn btn-ghost btn-sm" href="updateMatch.php?match_id=<?= (int) $m['id'] ?>">Update result</a>
                                <form method="post" action="../controller/matchController.php" data-confirm="Delete this match?">
                                    <?= csrf_field() ?><input type="hidden" name="match_id" value="<?= (int) $m['id'] ?>"><input type="hidden" name="t_id" value="<?= $id ?>">
                                    <button class="btn btn-danger-ghost btn-sm" name="delete_match" value="1" type="submit">Delete</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (!$matches): ?>
                        <?= empty_state('calendar', 'No matches scheduled', $canManage ? 'Register at least two teams, then schedule the first fixture.' : 'Fixtures will be published by the organiser.') ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reviews -->
            <div class="tab-panel" id="reviews">
                <div class="card">
                    <h2>Ratings &amp; reviews</h2>
                    <?php if ($totalReviews): ?>
                        <div class="review-score">
                            <div class="center"><div class="big"><?= number_format((float) $t['avg_rating'], 1) ?></div><?= stars_html($t['avg_rating']) ?><div class="muted small"><?= $totalReviews ?> reviews</div></div>
                            <div class="bars">
                                <?php foreach ($breakdown as $star => $n): ?>
                                    <div class="bar-row"><span><?= $star ?>★</span><div class="progress"><i style="width:<?= $totalReviews ? round($n / $totalReviews * 100) : 0 ?>%"></i></div><span><?= $n ?></span></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!$alreadyReviewed): ?>
                        <form method="post" action="../controller/commentController.php" class="mt-3">
                            <?= csrf_field() ?><input type="hidden" name="tournament_id" value="<?= $id ?>">
                            <div class="field">
                                <label>Your rating</label>
                                <div class="rating-input">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>><label for="star<?= $i ?>" title="<?= $i ?> stars">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="field"><textarea class="input" name="comment" placeholder="Share your experience…" required maxlength="1000"></textarea></div>
                            <button class="btn btn-primary" type="submit" name="postComment" value="1">Post review</button>
                        </form>
                    <?php else: ?>
                        <p class="muted small mt-2"><?= icon('check') ?> You've already reviewed this tournament.</p>
                    <?php endif; ?>

                    <div class="mt-3">
                        <?php foreach ($comments as $c): ?>
                            <div class="review">
                                <?= avatar_html($c['name'], $c['profile_picture'], 42) ?>
                                <div style="flex:1;min-width:0">
                                    <div class="row row-between wrap"><strong><?= e($c['name']) ?></strong><span class="muted small"><?= e(time_ago($c['created_at'])) ?></span></div>
                                    <?= stars_html($c['rating']) ?>
                                    <p style="margin:6px 0 0"><?= e($c['comment']) ?></p>
                                </div>
                                <?php if ($user['role'] === 'Admin' || (int) $c['user_id'] === (int) $user['id']): ?>
                                    <form method="post" action="../controller/commentController.php" data-confirm="Delete this review?">
                                        <?= csrf_field() ?><input type="hidden" name="comment_id" value="<?= (int) $c['id'] ?>"><input type="hidden" name="tournament_id" value="<?= $id ?>">
                                        <button class="icon-btn" name="deleteComment" value="1" title="Delete review" type="submit"><?= icon('trash') ?></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <?php if (!$comments): ?><?= empty_state('message', 'No reviews yet', 'Be the first to share feedback.') ?><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <aside class="stack">
            <div class="card">
                <h3>Register your team</h3>
                <?php if ($t['status'] === 'Completed'): ?>
                    <p class="muted small mb-0">This tournament has finished. Registration is closed.</p>
                <?php elseif ($isFull): ?>
                    <p class="muted small mb-0">All <?= (int) $t['max_teams'] ?> slots are taken.</p>
                <?php elseif ($eligible): ?>
                    <form method="post" action="../controller/teamController.php">
                        <?= csrf_field() ?><input type="hidden" name="tournament_id" value="<?= $id ?>">
                        <div class="field"><select class="input" name="team_id" required>
                            <?php foreach ($eligible as $tm): ?><option value="<?= (int) $tm['id'] ?>"><?= e($tm['name']) ?></option><?php endforeach; ?>
                        </select></div>
                        <button class="btn btn-primary btn-block" type="submit" name="join" value="1">Join tournament</button>
                    </form>
                <?php else: ?>
                    <p class="muted small">You need a <strong><?= e($t['category']) ?></strong> team that isn't registered yet.</p>
                    <a class="btn btn-ghost btn-block" href="createTeam.php">Create a team</a>
                <?php endif; ?>
                <?php foreach ($myRegistered as $tm): ?>
                    <?php if ($t['status'] !== 'Completed'): ?>
                        <form method="post" action="../controller/teamController.php" class="mt-2" data-confirm="Withdraw <?= e($tm['name']) ?> from this tournament?">
                            <?= csrf_field() ?><input type="hidden" name="tournament_id" value="<?= $id ?>"><input type="hidden" name="team_id" value="<?= (int) $tm['id'] ?>">
                            <div class="row row-between small"><span><?= icon('check') ?> <?= e($tm['name']) ?> registered</span><button class="btn btn-danger-ghost btn-sm" name="leave" value="1" type="submit">Withdraw</button></div>
                        </form>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="card">
                <h3>Documents</h3>
                <?php foreach ($attachments as $f): ?>
                    <a class="list-item" href="../uploads/docs/<?= e($f['file_path']) ?>" target="_blank" rel="noopener"><?= icon('file-text') ?> <span><?= e($f['file_name']) ?></span></a>
                <?php endforeach; ?>
                <?php if (!$attachments): ?><p class="muted small mb-0">No rulebook uploaded yet.</p><?php endif; ?>
            </div>

            <div class="card">
                <h3>Registration</h3>
                <div class="progress" style="height:10px"><i style="width:<?= min(100, round(count($registered) / max(1, (int) $t['max_teams']) * 100)) ?>%"></i></div>
                <p class="muted small mt-1 mb-0"><?= count($registered) ?> of <?= (int) $t['max_teams'] ?> team slots filled</p>
            </div>
        </aside>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
