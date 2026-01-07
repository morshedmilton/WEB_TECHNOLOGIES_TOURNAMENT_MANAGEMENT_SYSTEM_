<?php
session_start();
require_once('../model/userModel.php');

// Security Check: Only Admin can access
if (!isset($_COOKIE['status']) || $_SESSION['role'] !== 'Admin') {
    header('location: home.php?error=unauthorized');
}

$users = getAllUsers();
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 800px; margin: 30px auto;">
        <legend>System User List</legend>
        <div style="text-align: center;"><a href="home.php">Back to Dashboard</a></div>
        <br>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; text-align: center;">
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $u) { ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= $u['name'] ?></td>
                    <td><?= $u['username'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= $u['role'] ?></td>
                    <td><?= $u['status'] ?></td>
                    <td><a href="editUser.php?id=<?= $u['id'] ?>" style="display: inline;">Change Role/Status</a></td>
                </tr>
            <?php } ?>
        </table>
    </fieldset>
</body>

</html>