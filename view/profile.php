<?php
session_start();
require_once('../model/userModel.php');

if (!isset($_COOKIE['status'])) {
    header('location: login.php');
}

// Get current user information
$user = getUserByUsername($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 500px; margin: 50px auto;">
        <legend>My Profile</legend>
        <div style="text-align: center;">
            <a href="home.php">Back to Dashboard</a>
        </div>
        <hr>

        <div style="text-align: center;">
            <?php
            $pp = $user['profile_picture'] ? $user['profile_picture'] : 'default_user.png';
            ?>
            <img src="../uploads/users/<?php echo $pp; ?>" width="100" height="100"
                style="border-radius: 50%; border: 2px solid #000;">
            <br><br>

            <form action="../controller/profilePictureController.php" method="POST" enctype="multipart/form-data">
                <label>Change Profile Picture:</label><br>
                <input type="file" name="profile_pic" required>
                <button type="submit" name="upload">Upload</button>
            </form>
        </div>

        <hr>
        <table cellpadding="5">
            <tr>
                <td><strong>Name:</strong></td>
                <td><?php echo $user['name']; ?></td>
            </tr>
            <tr>
                <td><strong>Username:</strong></td>
                <td><?php echo $user['username']; ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><?php echo $user['email']; ?></td>
            </tr>
            <tr>
                <td><strong>Role:</strong></td>
                <td><?php echo $user['role']; ?></td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            <a href="editUser.php?id=<?php echo $user['id']; ?>">Edit Profile</a> |
            <a href="changePassword.php">Change Password</a>
        </div>
    </fieldset>
</body>

</html>