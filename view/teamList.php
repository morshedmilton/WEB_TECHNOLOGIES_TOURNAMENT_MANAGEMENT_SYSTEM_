<?php
session_start();
require_once('../model/teamModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: login.php');
}
$teams = getAllTeams();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Team List</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 800px; margin: 30px auto;">
        <legend>Manage Teams</legend>
        <div style="text-align: center;"><a href="home.php">Dashboard</a> | <a href="createTeam.php"> Create New
                Team</a>
        </div>
        <br>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; text-align: center;">
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Team Name</th>
                <th>Members</th>
                <th>Created By</th>
            </tr>
            <?php foreach ($teams as $team): ?>
                <tr>
                    <td><?php echo $team['id']; ?></td>
                    <td><?php echo $team['name']; ?></td>
                    <td><?php echo $team['members']; ?></td>
                    <td><?php echo $team['created_by']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </fieldset>
</body>

</html>