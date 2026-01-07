<?php
session_start();
require_once('../model/tournamentModel.php');

if (!isset($_COOKIE['status']) || $_SESSION['role'] !== 'Admin') {
    header('location: home.php?error=unauthorized');
    exit();
}

$logs = getAllActivities();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>System Activity Logs</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 800px; margin: 30px auto;">
        <legend>System Audit Trail / Activity Logs</legend>
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="home.php" style="display: inline;">Dashboard</a> |
            <a href="allUser.php" style="display: inline;">User Management</a>
        </div>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; text-align: left;">
            <thead>
                <tr style="background-color: #f2f2f2; text-align: center;">
                    <th>ID</th>
                    <th>Activity Description</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($logs) > 0): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $log['id']; ?></td>
                            <td><?php echo $log['activity_text']; ?></td>
                            <td style="text-align: center; font-size: 14px;">
                                <?php echo date('M d, Y - h:i A', strtotime($log['timestamp'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No activities recorded yet!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</body>

</html>