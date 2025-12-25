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
    <title>Edit User Role/Status</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/adminController.php">
        <fieldset style="width: 400px; margin: 50px auto;">
            <legend>Edit User: <?php echo $user['username']; ?></legend>
            <div style="text-align: center;"><a href="allUser.php">Back to List</a></div>
            <hr>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            Name: <strong><?php echo $user['name']; ?></strong><br><br>

            Role:
            <select name="role" style="width: 100%;">
                <option value="Admin" <?php if ($user['role'] == 'Admin')
                    echo 'selected'; ?>>Admin</option>
                <option value="Organizer" <?php if ($user['role'] == 'Organizer')
                    echo 'selected'; ?>>Organizer</option>
                <option value="Player" <?php if ($user['role'] == 'Player')
                    echo 'selected'; ?>>Player</option>
            </select><br><br>

            Status:
            <select name="status" style="width: 100%;">
                <option value="Active" <?php if ($user['status'] == 'Active')
                    echo 'selected'; ?>>Active</option>
                <option value="Blocked" <?php if ($user['status'] == 'Blocked')
                    echo 'selected'; ?>>Blocked</option>
            </select><br><br>

            <input type="submit" name="update_user" value="Update User">
        </fieldset>
    </form>
</body>

</html>