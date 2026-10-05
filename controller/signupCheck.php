<?php
require_once '../model/userModel.php';
require_once '../model/activityModel.php';
require_post('signup.php');

$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirmPassword'] ?? '';
$role = ($_POST['role'] ?? 'Player') === 'Organizer' ? 'Organizer' : 'Player';

$_SESSION['old'] = ['name' => $name, 'username' => $username, 'email' => $email, 'role' => $role];

$error = null;
if ($name === '' || $username === '' || $email === '' || $password === '') {
    $error = 'Please fill in all fields.';
} elseif (!preg_match('/^[A-Za-z0-9._-]{3,30}$/', $username)) {
    $error = 'Username must be 3-30 characters: letters, numbers, dot, dash or underscore.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address.';
} elseif (strlen($password) < 8) {
    $error = 'Password must be at least 8 characters.';
} elseif ($password !== $confirm) {
    $error = 'Passwords do not match.';
} elseif (!isUserUnique($username, $email)) {
    $error = 'That username or email is already registered.';
}

if ($error) {
    flash('error', $error);
    redirect('../view/signup.php');
}

unset($_SESSION['old']);
$id = createUser($name, $username, $email, $password, $role);
logActivity("New user registered: $username", $id);
flash('success', 'Account created! You can sign in now.');
redirect('../view/login.php');
