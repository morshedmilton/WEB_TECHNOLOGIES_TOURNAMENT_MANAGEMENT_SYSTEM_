<?php
require_once '../model/userModel.php';
require_once '../model/activityModel.php';
$user = require_login('../view/');
require_post('profile.php');

if (isset($_POST['upload'])) {
    $err = null;
    $name = save_upload($_FILES['profile_pic'] ?? [], __DIR__ . '/../uploads/users', 'image', 'user_' . $user['id'], $err);
    if ($name) {
        updateProfilePicture($user['id'], $name);
        flash('success', 'Profile photo updated.');
    } else {
        flash('error', $err ?: 'Please choose an image to upload.');
    }
} elseif (isset($_POST['profile'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Please provide your name and a valid email.');
    } elseif (emailTakenByOther($email, $user['id'])) {
        flash('error', 'That email is used by another account.');
    } else {
        updateProfile($user['id'], $name, $email);
        flash('success', 'Profile updated.');
    }
}
redirect('../view/profile.php');
