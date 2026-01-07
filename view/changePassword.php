<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Change Password - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/changePasswordCheck.php" enctype="multipart/form-data"
        onsubmit="return validateChangePassword()">
        <fieldset>
            <legend>Change Security Password</legend>
            <div style="text-align: center; margin-bottom: 20px;">
                <a href="profile.php" style="display: inline;">Back to Profile</a> |
                <a href="home.php" style="display: inline;">Dashboard</a>
            </div>
            Current Password: <input type="password" name="currentPassword" id="currentPassword" value="" /> <br>
            New Password: <input type="password" name="newPassword" id="newPassword" value="" /> <br>
            Confirm New Password: <input type="password" name="confirmNewPassword" id="confirmNewPassword" value="" />
            <br>
            <input type="submit" name="submit" value="Change Password" />
            <p id="jsError" style="color: red; text-align: center;">
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'invalid_current')
                        echo "Current password is incorrect!";
                    if ($_GET['error'] == 'mismatch')
                        echo "New passwords do not match!";
                    if ($_GET['error'] == 'null')
                        echo "Fill all fields!";
                    if ($_GET['error'] == 'db_error')
                        echo "Database error!";
                }
                ?>
            </p>
        </fieldset>
    </form>
    <script src="../asset/js/validation.js"></script>
</body>

</html>