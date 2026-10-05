<?php
require_once '../model/userModel.php';
$user = require_role('Admin');
$q = trim($_GET['q'] ?? '');
$users = getAllUsers($q);
$counts = countUsersByRole();
$active = 'users';
$pageTitle = 'User management';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head">
        <div><h1>Users</h1><p><?= array_sum($counts) ?> accounts · <?= $counts['Organizer'] ?> organisers · <?= $counts['Player'] ?> players</p></div>
        <form method="get" class="searchbar" style="max-width:340px"><input class="input" type="search" name="q" value="<?= e($q) ?>" placeholder="Search name, username, email…"></form>
    </div>
    <div class="card card-flush">
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><div class="row"><?= avatar_html($u['name'], $u['profile_picture'], 36) ?><div><strong><?= e($u['name']) ?></strong><div class="muted small">@<?= e($u['username']) ?></div></div></div></td>
                        <td><?= e($u['email']) ?></td>
                        <td><?= status_badge($u['role']) ?></td>
                        <td><?= status_badge($u['status']) ?></td>
                        <td class="muted"><?= e(fmt_date($u['created_at'])) ?></td>
                        <td class="right"><a class="btn btn-ghost btn-sm" href="editUser.php?id=<?= (int) $u['id'] ?>">Manage</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (!$users): ?><?= empty_state('search', 'No users match your search') ?><?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
