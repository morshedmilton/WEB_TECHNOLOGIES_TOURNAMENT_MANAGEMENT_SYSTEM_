<?php
require_once '../model/userModel.php';
require_once '../model/activityModel.php';
require_post('login.php');

$identifier = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($identifier === '' || $password === '') {
    flash('error', 'Please enter both username and password.');
    redirect('../view/login.php');
}

// Basic brute-force throttle: 8 failed attempts per session per 10 minutes.
$_SESSION['attempts'] = array_filter($_SESSION['attempts'] ?? [], function ($t) {
    return $t > time() - 600;
});
if (count($_SESSION['attempts']) >= 8) {
    flash('error', 'Too many attempts. Please wait a few minutes and try again.');
    redirect('../view/login.php');
}

$user = authenticate($identifier, $password);
if ($user === null) {
    flash('error', 'This account has been blocked. Contact an administrator.');
    redirect('../view/login.php');
}
if ($user === false) {
    $_SESSION['attempts'][] = time();
    flash('error', 'Invalid username or password.');
    redirect('../view/login.php');
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
unset($_SESSION['attempts']);
logActivity($user['username'] . ' signed in', $user['id']);
flash('success', 'Welcome back, ' . explode(' ', $user['name'])[0] . '!');
redirect('../view/home.php');
