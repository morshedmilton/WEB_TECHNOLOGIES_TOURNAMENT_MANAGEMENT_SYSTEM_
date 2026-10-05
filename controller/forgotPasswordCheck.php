<?php
require_once '../model/userModel.php';
require_post('forgotPassword.php');

$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('error', 'Please enter a valid email address.');
    redirect('../view/forgotPassword.php');
}

// Do not reveal whether the address exists (prevents account enumeration).
// Mail delivery is intentionally stubbed in this demo build.
getUserByEmail($email);
flash('success', 'If that email is registered, a reset link is on its way.');
redirect('../view/login.php');
