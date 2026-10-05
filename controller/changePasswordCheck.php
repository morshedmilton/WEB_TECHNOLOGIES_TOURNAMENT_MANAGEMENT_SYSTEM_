<?php
require_once '../model/userModel.php';
require_once '../model/activityModel.php';
$user = require_login('../view/');
require_post('changePassword.php');

$current = $_POST['currentPassword'] ?? '';
$new = $_POST['newPassword'] ?? '';
$confirm = $_POST['confirmNewPassword'] ?? '';

if ($current === '' || $new === '' || $confirm === '') {
    flash('error', 'All fields are required.');
} elseif (strlen($new) < 8) {
    flash('error', 'New password must be at least 8 characters.');
} elseif ($new !== $confirm) {
    flash('error', 'New passwords do not match.');
} elseif (!authenticate($user['username'], $current)) {
    flash('error', 'Your current password is incorrect.');
} else {
    updatePassword($user['id'], $new);
    logActivity($user['username'] . ' changed their password');
    flash('success', 'Password updated successfully.');
    redirect('../view/profile.php');
}
redirect('../view/changePassword.php');
