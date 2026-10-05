<?php
require_once '../model/helpers.php';
$user = require_login();
$pageTitle = 'Change password';
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="profile.php">Profile</a> / Security</div>
    <div class="page-head"><div><h1>Change password</h1><p>Use a strong password you don't use anywhere else.</p></div></div>
    <div class="card" style="max-width:520px">
        <form method="post" action="../controller/changePasswordCheck.php" data-validate="password" novalidate>
            <?= csrf_field() ?>
            <div class="field"><label for="currentPassword">Current password</label><input class="input" type="password" name="currentPassword" id="currentPassword" autocomplete="current-password"></div>
            <div class="field"><label for="newPassword">New password</label><input class="input" type="password" name="newPassword" id="newPassword" autocomplete="new-password"><div class="hint">At least 8 characters.</div></div>
            <div class="field"><label for="confirmNewPassword">Confirm new password</label><input class="input" type="password" name="confirmNewPassword" id="confirmNewPassword" autocomplete="new-password"></div>
            <div class="form-error"></div>
            <button class="btn btn-primary btn-lg" type="submit" name="submit" value="1">Update password</button>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
