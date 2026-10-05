<?php
require_once '../model/helpers.php';
$user = current_user();
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
$active = '';
$pageTitle = 'Contact support';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head"><div><h1>Contact support</h1><p>Questions, bugs or feedback? We usually reply within a day.</p></div></div>
    <div class="grid grid-main">
        <div class="card">
            <form method="post" action="../controller/contactCheck.php">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <div class="field"><label for="name">Name</label><input class="input" type="text" name="name" id="name" value="<?= e($old['name'] ?? ($user['name'] ?? '')) ?>" required></div>
                    <div class="field"><label for="email">Email</label><input class="input" type="email" name="email" id="email" value="<?= e($old['email'] ?? ($user['email'] ?? '')) ?>" required></div>
                </div>
                <div class="field"><label for="subject">Subject</label><input class="input" type="text" name="subject" id="subject" maxlength="150" value="<?= e($old['subject'] ?? '') ?>" required></div>
                <div class="field"><label for="message">Message</label><textarea class="input" name="message" id="message" rows="6" maxlength="2000" required><?= e($old['message'] ?? '') ?></textarea></div>
                <button class="btn btn-primary btn-lg" type="submit" name="submit" value="1">Send message</button>
            </form>
        </div>
        <aside class="card">
            <h3>Other ways to get help</h3>
            <p class="muted small">Check the <a href="faq.php">FAQ</a> for answers to common questions about hosting tournaments, joining as a team and managing your account.</p>
            <div class="list-item"><span class="stat-icon tone-indigo" style="width:40px;height:40px"><?= icon('mail') ?></span><div><strong>Email</strong><div class="muted small">support@tourneyhub.dev</div></div></div>
            <div class="list-item"><span class="stat-icon tone-green" style="width:40px;height:40px">⏱</span><div><strong>Response time</strong><div class="muted small">Within 24 hours</div></div></div>
        </aside>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
