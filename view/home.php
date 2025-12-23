<?php
session_start();
// If not logged in, redirect to login page
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 600px;">
        <legend>Tournament Management Dashboard</legend>

        <h2 style="text-align: center;">Welcome Home, <?= $_SESSION['username'] ?>!</h2>

        <hr>
        <div style="text-align: center;">
            <a href='home.php' style="display: inline;">Home</a> |
            <a href='profile.php' style="display: inline;">My Profile</a> |
            <a href='tournamentList.php' style="display: inline;">Tournaments</a> |

            <a href='allUser.php' style="display: inline;">User Management</a> |
            <a href='reports.php' style="display: inline;">Reports</a> |

            <a href='../controller/logout.php' style="display: inline; color: red;">Logout</a>
        </div>
        <hr>

        <div style="display: flex; justify-content: space-around; padding: 20px;">
            <div style="border: 1px solid #ccc; padding: 10px; width: 40%; text-align: center;">
                <h3>Active Tournaments</h3>
                <p>5</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 10px; width: 40%; text-align: center;">
                <h3>Today's Activity</h3>
                <p>12 Updates</p>
            </div>
        </div>
    </fieldset>
</body>

</html>