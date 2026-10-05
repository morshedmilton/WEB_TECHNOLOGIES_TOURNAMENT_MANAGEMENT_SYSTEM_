<?php
require_once '../model/helpers.php';
if (is_logged_in()) {
    redirect('home.php');
}
$authLayout = true;
$pageTitle = 'Sign in';
include 'partials/header.php';
?>
<div class="auth">
    <aside class="auth-art">
        <a class="brand" href="landing.php"><span class="brand-logo"><?= icon('trophy') ?></span> TourneyHub</a>
        <div>
            <h2>Welcome back to the arena.</h2>
            <p>Manage tournaments, track your team's fixtures and follow live standings, all in one place.</p>
        </div>
        <span class="floaty" style="right:8%;top:14%"><?= icon('cricket') ?></span>
        <span class="floaty" style="right:30%;bottom:10%;font-size:5rem"><?= icon('football') ?></span>
        <span class="floaty" style="right:4%;bottom:26%;font-size:4rem"><?= icon('gamepad') ?></span>
        <span class="muted small" style="color:#9fb0dc">© <?= date('Y') ?> TourneyHub</span>
    </aside>
    <section class="auth-form">
        <div class="auth-box">
            <h1>Sign in</h1>
            <p class="muted">Use your username or email to continue.</p>
            <?php foreach (flash_pull() as $f): ?>
                <div class="toast <?= e($f['type']) ?>" style="margin-bottom:16px"><span><?= e($f['message']) ?></span></div>
            <?php endforeach; ?>
            <form id="loginForm" method="post" action="../controller/loginCheck.php" data-validate="login" novalidate>
                <?= csrf_field() ?>
                <div class="field">
                    <label for="username">Username or email</label>
                    <input class="input" type="text" name="username" id="username" autocomplete="username" autofocus>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input class="input" type="password" name="password" id="password" autocomplete="current-password">
                </div>
                <div class="form-error"></div>
                <button class="btn btn-primary btn-block btn-lg" type="submit" name="submit" value="1">Sign in</button>
                <div class="row row-between small mt-2">
                    <a href="forgotPassword.php">Forgot password?</a>
                    <span class="muted">New here? <a href="signup.php">Create an account</a></span>
                </div>
            </form>
            <div class="demo-box">
                <strong class="small"><?= icon('rocket') ?> Recruiter / demo access</strong>
                <p class="muted small" style="margin:4px 0 6px">One click to explore each role with realistic seeded data.</p>
                <button class="btn btn-ghost btn-sm" type="button" data-demo-user="admin" data-demo-pass="Admin@123">Admin</button>
                <button class="btn btn-ghost btn-sm" type="button" data-demo-user="organizer" data-demo-pass="Organizer@123">Organizer</button>
                <button class="btn btn-ghost btn-sm" type="button" data-demo-user="player" data-demo-pass="Player@123">Player</button>
            </div>
        </div>
    </section>
</div>
<?php include 'partials/footer.php'; ?>
