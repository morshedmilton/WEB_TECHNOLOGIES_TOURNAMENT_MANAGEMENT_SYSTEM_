<?php
require_once '../model/userModel.php';
require_once '../model/activityModel.php';
require_post('contact.php');

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$_SESSION['old'] = compact('name', 'email', 'subject', 'message');

if ($name === '' || $subject === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('error', 'Please complete every field with a valid email address.');
    redirect('../view/contact.php');
}
unset($_SESSION['old']);
saveContactMessage(mb_substr($name, 0, 100), $email, mb_substr($subject, 0, 150), mb_substr($message, 0, 2000));
logActivity("Support message received from $name");
flash('success', "Message sent. We'll get back to you soon.");
redirect('../view/contact.php');
