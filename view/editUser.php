<?php
session_start();
require_once('../model/userModel.php');
if (!isset($_COOKIE['status']) || $_SESSION['role'] !== 'Admin') {
    header('location: home.php');
    exit();
}
if (isset($_GET['id'])) {
    $user = getUserById($_GET['id']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/adminController.php">
        <fieldset>
            <legend>Update User: <?= $user['username'] ?></legend>
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            Role: <select name="role">
                <option value="Admin" <?= ($user['role'] == 'Admin') ? 'selected' : '' ?>>Admin</option>
                <option value="Organizer" <?= ($user['role'] == 'Organizer') ? 'selected' : '' ?>>Organizer</option>
                <option value="Player" <?= ($user['role'] == 'Player') ? 'selected' : '' ?>>Player</option>
            </select><br>
            Status: <select name="status">
                <option value="Active" <?= ($user['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                <option value="Blocked" <?= ($user['status'] == 'Blocked') ? 'selected' : '' ?>>Blocked</option>
            </select><br><br>
            <input type="submit" name="update_user" value="Save Changes">
        </fieldset>
    </form>
</body>

</html>