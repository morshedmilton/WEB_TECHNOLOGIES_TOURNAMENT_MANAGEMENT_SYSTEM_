<?php
require_once '../model/userModel.php';
$admin = require_role('Admin');
$u = getUserById((int) ($_GET['id'] ?? 0));
if (!$u) {
    flash('error', 'User not found.');
    redirect('allUser.php');
}
$active = 'users';
$pageTitle = 'Manage ' . $u['username'];
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="allUser.php">Users</a> / <?= e($u['username']) ?></div>
    <div class="page-head"><div><h1>Manage user</h1></div></div>
    <div class="card" style="max-width:520px">
        <div class="row" style="margin-bottom:22px"><?= avatar_html($u['name'], $u['profile_picture'], 56) ?><div><strong><?= e($u['name']) ?></strong><div class="muted small">@<?= e($u['username']) ?> · <?= e($u['email']) ?></div></div></div>
        <form method="post" action="../controller/adminController.php">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
            <div class="field"><label for="role">Role</label>
                <select class="input" name="role" id="role"><?php foreach (ROLES as $r): ?><option <?= $u['role'] === $r ? 'selected' : '' ?>><?= e($r) ?></option><?php endforeach; ?></select></div>
            <div class="field"><label for="status">Account status</label>
                <select class="input" name="status" id="status"><?php foreach (['Active', 'Blocked'] as $s): ?><option <?= $u['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
                <div class="hint">Blocked users can no longer sign in.</div></div>
            <div class="row"><button class="btn btn-primary btn-lg" type="submit" name="update_user" value="1">Save changes</button><a class="btn btn-ghost btn-lg" href="allUser.php">Cancel</a></div>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
