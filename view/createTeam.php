<?php
session_start();
if (!isset($_COOKIE['status'])) {
    header('location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Create Team</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/teamController.php">
        <fieldset>
            <legend>Register New Team</legend>
            <div style="text-align: center;"><a href="home.php">Dashboard</a></div>
            Team Name: <input type="text" name="name" required><br>
            Member Usernames (comma separated): <br>
            <textarea name="members" placeholder="user1, user2, user3" rows="3" style="width: 95%;"></textarea><br>
            <input type="submit" name="submit" value="Create Team">
            <p style="color: red;">
                <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_members')
                    echo "Some members are not valid Players!"; ?>
            </p>
        </fieldset>
    </form>
</body>

</html>