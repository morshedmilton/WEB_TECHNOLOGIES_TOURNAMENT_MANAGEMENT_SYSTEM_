<?php
require_once '../model/helpers.php';
if (is_logged_in()) {
    redirect('home.php');
}
$old = isset($_SESSION['old']) ? $_SESSION['old'] : [];
unset($_SESSION['old']);
$authLayout = true;
$pageTitle = 'Create account';
include 'partials/header.php';
?>
<div class="auth">
    <aside class="auth-art">
        <a class="brand" href="landing.php"><span class="brand-logo"><?= icon('trophy') ?></span> TourneyHub</a>
        <div>
            <h2>Host it. Join it. Win it.</h2>
            <p>Create a free account to organise tournaments, build a team and follow every result.</p>
        </div>
        <span class="floaty" style="right:10%;top:12%"><?= icon('basketball') ?></span>
        <span class="floaty" style="right:34%;bottom:12%;font-size:5rem"><?= icon('badminton') ?></span>
        <span class="floaty" style="right:5%;bottom:28%;font-size:4rem"><?= icon('volleyball') ?></span>
        <span class="muted small" style="color:#9fb0dc">© <?= date('Y') ?> TourneyHub</span>
    </aside>
    <section class="auth-form">
        <div class="auth-box">
            <h1>Create your account</h1>
            <p class="muted">It takes less than a minute.</p>
            <?php foreach (flash_pull() as $f): ?>
                <div class="toast <?= e($f['type']) ?>" style="margin-bottom:16px"><span><?= e($f['message']) ?></span></div>
            <?php endforeach; ?>
            <form method="post" action="../controller/signupCheck.php" data-validate="signup" novalidate>
                <?= csrf_field() ?>
                <div class="field">
                    <label for="name">Full name</label>
                    <input class="input" type="text" name="name" id="name" value="<?= e($old['name'] ?? '') ?>" autocomplete="name">
                </div>
                <div class="form-grid">
                    <div class="field">
                        <label for="username">Username</label>
                        <input class="input" type="text" name="username" id="username" value="<?= e($old['username'] ?? '') ?>" autocomplete="username">
                    </div>
                    <div class="field">
                        <label for="role">I am a</label>
                        <select class="input" name="role" id="role">
                            <option value="Player" <?= ($old['role'] ?? '') === 'Player' ? 'selected' : '' ?>>Player</option>
                            <option value="Organizer" <?= ($old['role'] ?? '') === 'Organizer' ? 'selected' : '' ?>>Organizer</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input class="input" type="email" name="email" id="email" value="<?= e($old['email'] ?? '') ?>" autocomplete="email">
                </div>
                <div class="form-grid">
                    <div class="field">
                        <label for="password">Password</label>
                        <input class="input" type="password" name="password" id="password" autocomplete="new-password">
                    </div>
                    <div class="field">
                        <label for="confirmPassword">Confirm</label>
                        <input class="input" type="password" name="confirmPassword" id="confirmPassword" autocomplete="new-password">
                    </div>
                </div>
                <div class="hint" style="margin-top:-8px;margin-bottom:12px">At least 8 characters.</div>
                <div class="form-error"></div>
                <button class="btn btn-primary btn-block btn-lg" type="submit" name="submit" value="1">Create account</button>
                <p class="small muted center mt-2">Already registered? <a href="login.php">Sign in</a></p>
            </form>
        </div>
    </section>
</div>
<?php include 'partials/footer.php'; ?>
