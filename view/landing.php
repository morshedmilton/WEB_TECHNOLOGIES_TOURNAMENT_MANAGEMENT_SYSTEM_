<?php
require_once '../model/tournamentModel.php';
require_once '../model/matchModel.php';

$totals = getSiteTotals();
$featured = getFeaturedTournaments(3);
$live = getUpcomingMatches(3);
$pageTitle = 'Run tournaments like a pro';
$navTransparent = true;
include 'partials/header.php';
?>
<section class="hero">
    <div class="blob b1"></div><div class="blob b2"></div>
    <div class="hero-floaters">
        <span style="left:4%;top:22%;--d:0s"><?= icon('cricket') ?></span>
        <span style="left:46%;top:8%;--d:-2s"><?= icon('football') ?></span>
        <span style="left:38%;top:68%;--d:-4s"><?= icon('gamepad') ?></span>
        <span style="right:3%;top:70%;--d:-1s"><?= icon('basketball') ?></span>
        <span style="left:12%;top:74%;--d:-5s"><?= icon('badminton') ?></span>
    </div>
    <div class="container">
        <div>
            <span class="hero-tag"><?= icon('zap') ?> Live brackets · Standings · Reviews</span>
            <h1>Run every tournament <em>like a pro.</em></h1>
            <p class="lead">TourneyHub is an all-in-one platform for organisers, teams and players: publish tournaments, register squads, schedule fixtures and watch the league table update the moment a result is in.</p>
            <div class="row wrap">
                <?php if (is_logged_in()): ?>
                    <a class="btn btn-primary btn-lg" href="home.php">Open dashboard →</a>
                <?php else: ?>
                    <a class="btn btn-primary btn-lg" href="signup.php">Create free account</a>
                    <a class="btn btn-ghost btn-lg" href="login.php">Try the live demo</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-card">
            <div class="row row-between" style="margin-bottom:14px">
                <strong>Next fixtures</strong><span class="badge badge-live" style="background:#fff">Live</span>
            </div>
            <?php foreach ($live as $m): echo match_row($m); endforeach; ?>
            <?php if (!$live): ?><p class="muted center">Fixtures will appear here.</p><?php endif; ?>
        </div>
    </div>
</section>

<div class="container stats-strip">
    <div class="card">
        <?php foreach ([[$totals['tournaments'], 'Tournaments hosted'], [$totals['teams'], 'Teams competing'], [$totals['players'], 'Registered players'], [$totals['matches'], 'Matches tracked']] as $s): ?>
            <div><div class="stat-value"><?= (int) $s[0] ?></div><div class="stat-label"><?= $s[1] ?></div></div>
        <?php endforeach; ?>
    </div>
</div>

<main>
    <section class="section container" id="features">
        <div class="section-title">
            <h2>Everything a tournament needs</h2>
            <p class="muted">Purpose-built tools for the three people in every competition.</p>
        </div>
        <div class="grid grid-3">
            <div class="card feature"><div class="stat-icon tone-indigo"><?= icon('calendar') ?></div><h3>Fixtures & results</h3><p>Schedule matches between registered teams, enter scores and let standings compute automatically.</p></div>
            <div class="card feature"><div class="stat-icon tone-green"><?= icon('users') ?></div><h3>Team management</h3><p>Build squads from registered players and enter them into any tournament in your sport with one click.</p></div>
            <div class="card feature"><div class="stat-icon tone-amber"><?= icon('star') ?></div><h3>Ratings & reviews</h3><p>Collect feedback from participants and surface the best-rated events on your dashboard.</p></div>
            <div class="card feature"><div class="stat-icon tone-blue"><?= icon('lock') ?></div><h3>Role-based access</h3><p>Admins, organisers and players each get the right tools, with an audit trail of every important action.</p></div>
            <div class="card feature"><div class="stat-icon tone-pink"><?= icon('bar-chart') ?></div><h3>Reports</h3><p>Admin analytics for tournaments by category, user mix and daily platform activity.</p></div>
            <div class="card feature"><div class="stat-icon tone-purple"><?= icon('cloud') ?></div><h3>Cloud-native</h3><p>Runs on a managed cloud MySQL database with TLS, containerised and ready to deploy anywhere.</p></div>
        </div>
    </section>

    <section class="section container" id="featured">
        <div class="section-title">
            <h2>Happening now</h2>
            <p class="muted">Live and upcoming tournaments on the platform.</p>
        </div>
        <div class="grid grid-3">
            <?php foreach ($featured as $t): ?>
                <?= tournament_card($t, is_logged_in() ? 'detailsTournament.php' : 'login.php') ?>
            <?php endforeach; ?>
        </div>
        <div class="cta">
            <h2>Ready to host your next tournament?</h2>
            <p style="opacity:.9">Sign up in under a minute. No credit card, no setup.</p>
            <a class="btn btn-lg" href="<?= is_logged_in() ? 'createTournament.php' : 'signup.php' ?>">Get started free</a>
        </div>
    </section>
</main>
<?php include 'partials/footer.php'; ?>
