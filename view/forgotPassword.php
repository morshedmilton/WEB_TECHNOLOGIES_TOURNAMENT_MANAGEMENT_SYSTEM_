<?php
require_once '../model/helpers.php';
$authLayout = true;
$pageTitle = 'Reset password';
include 'partials/header.php';
?>
<div class="auth">
    <aside class="auth-art">
        <a class="brand" href="landing.php"><span class="brand-logo"><?= icon('trophy') ?></span> TourneyHub</a>
        <div>
            <h2>Locked out? It happens.</h2>
            <p>Enter your account email and we'll send you a link to get back in.</p>
        </div>
        <span class="floaty" style="right:10%;top:14%"><?= icon('key') ?></span>
        <span class="muted small" style="color:#9fb0dc">© <?= date('Y') ?> TourneyHub</span>
    </aside>
    <section class="auth-form">
        <div class="auth-box">
            <h1>Reset your password</h1>
            <p class="muted">We'll email you a reset link if the address is registered.</p>
            <?php foreach (flash_pull() as $f): ?>
                <div class="toast <?= e($f['type']) ?>" style="margin-bottom:16px"><span><?= e($f['message']) ?></span></div>
            <?php endforeach; ?>
            <form method="post" action="../controller/forgotPasswordCheck.php">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="email">Email</label>
                    <input class="input" type="email" name="email" id="email" required autofocus>
                </div>
                <button class="btn btn-primary btn-block btn-lg" type="submit" name="submit" value="1">Send reset link</button>
                <p class="small center mt-2"><a href="login.php">← Back to sign in</a></p>
            </form>
        </div>
    </section>
</div>
<?php include 'partials/footer.php'; ?>
