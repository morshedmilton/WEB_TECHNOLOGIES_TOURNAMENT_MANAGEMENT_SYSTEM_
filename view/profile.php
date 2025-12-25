<?php
session_start();
require_once('../model/userModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: login.php');
}

$user = getUserByUsername($_SESSION['username']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset>
        <legend>Profile Details</legend>
        <div style="text-align: center;">
            <a href="home.php">Dashboard</a> | <a href="../controller/logout.php">Logout</a>
        </div>
        <hr>
        <p><strong>Name:</strong> <?= $user['name'] ?></p>
        <p><strong>Username:</strong> <?= $user['username'] ?></p>
        <p><strong>Email:</strong> <?= $user['email'] ?></p>
        <p><strong>Role:</strong> <?= $user['role'] ?></p>
        <p><strong>Status:</strong> <?= $user['status'] ?></p>
        <hr>
        <a href="editUser.php?id=<?= $user['id'] ?>" class="btn" style="color: white; text-align: center;">Edit
            Profile</a>
    </fieldset>
</body>

</html>