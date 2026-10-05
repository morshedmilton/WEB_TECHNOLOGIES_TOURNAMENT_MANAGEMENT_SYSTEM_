<?php
require_once '../model/teamModel.php';
$user = require_role(['Player', 'Organizer', 'Admin']);
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
$active = 'teams';
$pageTitle = 'Create team';
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="teamList.php">Teams</a> / New</div>
    <div class="page-head"><div><h1>Create a team</h1><p>You'll be added as captain automatically.</p></div></div>
    <div class="card" style="max-width:640px">
        <form method="post" action="../controller/teamController.php">
            <?= csrf_field() ?>
            <div class="field">
                <label for="name">Team name</label>
                <input class="input" type="text" name="name" id="name" maxlength="100" value="<?= e($old['name'] ?? '') ?>" placeholder="e.g. Dhaka Dynamos" required>
            </div>
            <div class="field">
                <label for="sport">Sport</label>
                <select class="input" name="sport" id="sport">
                    <?php foreach (CATEGORIES as $c): ?><option value="<?= e($c) ?>" <?= ($old['sport'] ?? '') === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
                </select>
                <div class="hint">Teams can only enter tournaments of their own sport.</div>
            </div>
            <div class="field">
                <label for="members">Teammates <span class="muted">(usernames, comma separated)</span></label>
                <textarea class="input" name="members" id="members" rows="3" placeholder="tanvir.hossain, sadia.islam, imran.khan"><?= e($old['members'] ?? '') ?></textarea>
                <div class="hint">Every teammate must already be a registered player.</div>
            </div>
            <button class="btn btn-primary btn-lg" type="submit" name="submit" value="1">Create team</button>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
