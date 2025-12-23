<?php
session_start();
// Login check
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Later user's current info will be fetched from database
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit User Role/Status - Admin</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>

    <form method="post" action="../controller/userController.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Edit User (Role & Status)</legend>

            <div style="text-align: center; margin-bottom: 20px;">
                <a href="allUser.php" style="display: inline;">Back to User List</a> |
                <a href="home.php" style="display: inline;">Dashboard</a>
            </div>

            <input type="hidden" name="id" value="<?php echo $id; ?>">

            Username: <input type="text" name="username" value="alamin" readonly /> <br>
            Email: <input type="email" name="email" value="alamin@aiub.edu" readonly /> <br>

            Role:
            <select name="role"
                style="width: 95%; padding: 8px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;">
                <option value="User">User</option>
                <option value="Admin">Admin</option>
            </select> <br>

            Status:
            <select name="status"
                style="width: 95%; padding: 8px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;">
                <option value="Active">Active</option>
                <option value="Blocked">Blocked</option>
            </select> <br>

            <input type="submit" name="updateUser" value="Save Changes" />
        </fieldset>
    </form>
</body>

</html>