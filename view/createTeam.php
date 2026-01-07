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
    <title>Form Team</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/teamController.php">
        <fieldset style="width: 500px; margin: 50px auto;">
            <legend>Create a New Team</legend>

            <div style="text-align: center; margin-bottom: 10px;">
                <a href="home.php">Dashboard</a> | <a href="teamList.php">Team List</a>
            </div>

            <div style="text-align: center; margin-bottom: 15px;">
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'null') {
                        echo "<span style='color: red; font-weight: bold;'>Team name cannot be empty!</span>";
                    } elseif ($_GET['error'] == 'invalid_members') {
                        echo "<span style='color: red; font-weight: bold;'>Error: One or more members are not registered as 'Player'!</span>";
                    } elseif ($_GET['error'] == 'db_error') {
                        echo "<span style='color: red; font-weight: bold;'>Database error! Please try again.</span>";
                    }
                }
                ?>
            </div>

            Team Name:
            <input type="text" name="name" placeholder="Enter team name" required><br>

            Members (Usernames, comma separated):
            <textarea name="members" rows="3" style="width: 95%;" placeholder="e.g. user1, user2, user3"></textarea>
            <small style="color: gray;">Note: All members must be registered in the system.</small><br><br>

            <input type="submit" name="submit" value="Create Team">
        </fieldset>
    </form>
</body>

</html>
