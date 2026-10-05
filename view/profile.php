<?php
require_once '../model/teamModel.php';
require_once '../model/userModel.php';
$user = require_login();
$full = getUserById($user['id']);
$teams = getMyTeams($user['id']);
$tournamentsCreated = (int) db_val('SELECT COUNT(*) FROM tournaments WHERE created_by = ?', [$user['id']]);
$reviews = (int) db_val('SELECT COUNT(*) FROM comments WHERE user_id = ?', [$user['id']]);
$active = '';
$pageTitle = 'My profile';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head"><div><h1>My profile</h1><p>Manage your personal information and security.</p></div></div>
    <div class="grid grid-main">
        <div class="stack">
            <div class="card">
                <h2>Personal information</h2>
                <form method="post" action="../controller/profilePictureController.php">
                    <?= csrf_field() ?><input type="hidden" name="profile" value="1">
                    <div class="form-grid">
                        <div class="field"><label for="name">Full name</label><input class="input" type="text" name="name" id="name" value="<?= e($full['name']) ?>" required></div>
                        <div class="field"><label for="email">Email</label><input class="input" type="email" name="email" id="email" value="<?= e($full['email']) ?>" required></div>
                        <div class="field"><label>Username</label><input class="input" value="@<?= e($full['username']) ?>" disabled></div>
                        <div class="field"><label>Role</label><input class="input" value="<?= e($full['role']) ?>" disabled></div>
                    </div>
                    <button class="btn btn-primary" type="submit">Save changes</button>
                    <a class="btn btn-ghost" href="changePassword.php"><?= icon('lock') ?> Change password</a>
                </form>
            </div>
        </div>
        <aside class="stack">
            <div class="card center">
                <div style="display:flex;justify-content:center"><?= avatar_html($full['name'], $full['profile_picture'], 104) ?></div>
                <h2 class="mt-2 mb-0"><?= e($full['name']) ?></h2>
                <p class="muted">@<?= e($full['username']) ?> · <?= status_badge($full['role']) ?></p>
                <form method="post" action="../controller/profilePictureController.php" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input class="input" type="file" name="profile_pic" accept=".jpg,.jpeg,.png,.webp" required>
                    <button class="btn btn-ghost btn-block mt-1" type="submit" name="upload" value="1">Upload new photo</button>
                </form>
            </div>
            <div class="card">
                <h3>Activity</h3>
                <div class="info-grid">
                    <div><div class="k">Teams</div><div class="v"><?= count($teams) ?></div></div>
                    <div><div class="k">Hosted</div><div class="v"><?= $tournamentsCreated ?></div></div>
                    <div><div class="k">Reviews</div><div class="v"><?= $reviews ?></div></div>
                    <div><div class="k">Member since</div><div class="v"><?= e(fmt_date($full['created_at'], 'M Y')) ?></div></div>
                </div>
            </div>
        </aside>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
