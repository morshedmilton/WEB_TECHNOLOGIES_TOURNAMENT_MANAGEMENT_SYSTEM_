<?php
/**
 * Page chrome. Set before including:
 *   $pageTitle (string), $active (nav key), $navTransparent (bool, landing page)
 */
require_once __DIR__ . '/components.php';
$user = current_user();
$active = isset($active) ? $active : '';
$pageTitle = isset($pageTitle) ? $pageTitle : 'TourneyHub';
$navTransparent = !empty($navTransparent);

$links = [];
if ($user) {
    $links[] = ['home', 'home.php', 'Dashboard'];
    $links[] = ['tournaments', 'tournamentList.php', 'Tournaments'];
    $links[] = ['teams', 'teamList.php', 'Teams'];
    if ($user['role'] === 'Admin') {
        $links[] = ['users', 'allUser.php', 'Users'];
        $links[] = ['reports', 'systemReports.php', 'Reports'];
        $links[] = ['activity', 'activityLog.php', 'Activity'];
    } else {
        $links[] = ['matches', 'myMatches.php', 'My Matches'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> · TourneyHub</title>
    <meta name="description" content="TourneyHub - plan tournaments, register teams, schedule matches and track live standings.">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236366f1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22M18 2H6v7a6 6 0 0 0 12 0V2Z'/%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        (function () {
            try {
                var q = new URLSearchParams(location.search).get('theme');
                if (q === 'light' || q === 'dark') localStorage.setItem('theme', q);
                var t = localStorage.getItem('theme');
                if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) { document.documentElement.setAttribute('data-theme', 'light'); }
        })();
        document.documentElement.classList.add('js');
        try { if (!sessionStorage.getItem('splash')) document.documentElement.classList.add('splash'); } catch (e) { }
    </script>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <div id="splash" aria-hidden="true"><div><div class="splash-logo"><?= icon('trophy') ?></div><div class="splash-name">TourneyHub</div><div class="splash-bar"><i></i></div></div></div>
    <div id="topbar"></div>
    <?php if (empty($authLayout)): ?>
    <header class="nav<?= $navTransparent ? ' transparent' : '' ?>">
        <div class="container">
            <a class="brand" href="<?= $user ? 'home.php' : 'landing.php' ?>"><span class="brand-logo"><?= icon('trophy') ?></span> TourneyHub</a>
            <?php if ($user): ?>
                <nav class="nav-links">
                    <?php foreach ($links as $l): ?>
                        <a href="<?= $l[1] ?>" class="<?= $active === $l[0] ? 'active' : '' ?>"><?= $l[2] ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php else: ?>
                <nav class="nav-links">
                    <a href="landing.php#features">Features</a>
                    <a href="landing.php#featured">Tournaments</a>
                    <a href="faq.php">FAQ</a>
                    <a href="contact.php">Contact</a>
                </nav>
            <?php endif; ?>
            <div class="nav-right">
                <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle dark mode"><?= icon('moon', 18, 't-moon') ?><?= icon('sun', 18, 't-sun') ?></button>
                <?php if ($user): ?>
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" aria-haspopup="true">
                            <?= avatar_html($user['name'], $user['profile_picture'], 32) ?>
                            <span class="uname"><?= e(explode(' ', $user['name'])[0]) ?></span>
                        </button>
                        <div class="dropdown-menu">
                            <div class="who">
                                <strong><?= e($user['name']) ?></strong><br>
                                <span class="muted small">@<?= e($user['username']) ?></span> · <?= status_badge($user['role']) ?>
                            </div>
                            <a href="profile.php"><?= icon('user') ?> My profile</a>
                            <a href="changePassword.php"><?= icon('lock') ?> Change password</a>
                            <a href="faq.php"><?= icon('help') ?> Help & FAQ</a>
                            <a href="contact.php"><?= icon('mail') ?> Contact support</a>
                            <a class="danger" href="../controller/logout.php"><?= icon('log-out') ?> Sign out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a class="btn btn-ghost btn-sm" href="login.php">Sign in</a>
                    <a class="btn btn-primary btn-sm" href="signup.php">Get started</a>
                <?php endif; ?>
                <button class="icon-btn menu-btn" id="menuBtn" type="button" aria-label="Menu"><?= icon('menu') ?></button>
            </div>
        </div>
    </header>
    <?php endif; ?>
