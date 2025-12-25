<?php
session_start();
require_once('../model/tournamentModel.php');

if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}

// Real-time data fetching
$activeTournaments = getActiveTournamentCount();
$todayActivity = getTodayActivityCount();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Organizer'; // Default role
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 850px; margin: 30px auto;">
        <legend>Dashboard - Welcome, <?php echo $_SESSION['username']; ?></legend>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="home.php" style="display: inline;">Home</a> |
            <a href="profile.php" style="display: inline;">Profile</a> |
            <a href="tournamentList.php" style="display: inline;">Tournaments</a> |
            <a href="../controller/logout.php" style="display: inline; color: red;">Logout</a>
        </div>

        <div style="display: flex; justify-content: space-around; margin-bottom: 30px;">
            <div style="border: 2px solid #000; padding: 15px; width: 40%; text-align: center; border-radius: 10px;">
                <h3 style="margin: 0;">Active Tournaments</h3>
                <h1 style="color: #4CAF50;"><?php echo $activeTournaments; ?></h1>
            </div>
            <div style="border: 2px solid #000; padding: 15px; width: 40%; text-align: center; border-radius: 10px;">
                <h3 style="margin: 0;">Today's Activity</h3>
                <h1 style="color: #0066cc;"><?php echo $todayActivity; ?></h1>
            </div>
        </div>

        <hr>

        <div style="display: flex; justify-content: space-between; padding: 10px;">

            <div style="width: 48%;">
                <h4>Tournament Management</h4>
                <ul>
                    <li><a href="createTournament.php" style="text-align: left;">Host New Tournament</a></li>
                    <li><a href="tournamentList.php" style="text-align: left;">Manage My Tournaments</a></li>
                    <li><a href="teamList.php" style="text-align: left;">View All Teams</a></li>
                </ul>
            </div>

            <?php if ($role == 'Admin') { ?>
                <div style="width: 48%; border-left: 1px solid #ccc; padding-left: 20px;">
                    <h4>Admin Control Panel</h4>
                    <ul>
                        <li><a href="allUser.php" style="text-align: left;">System User List</a></li>
                        <li><a href="activityLog.php" style="text-align: left;">View Activity Logs</a></li>
                        <li><a href="systemReports.php" style="text-align: left;">Generate Reports</a></li>
                    </ul>
                </div>
            <?php } ?>

        </div>

        <hr>

        <div style="text-align: center; padding: 10px;">
            <a href="contact.php" style="display: inline;">Contact Admin</a> |
            <a href="faq.php" style="display: inline;">Help & FAQ</a> |
            <a href="changePassword.php" style="display: inline;">Security Settings</a>
        </div>
    </fieldset>

    <div style="text-align: center; margin-top: 10px;">
        <button onclick="toggleTheme()" style="padding: 5px 15px; cursor: pointer;">Toggle Dark/Light Mode</button>
    </div>

    <script src="../asset/js/themeToggle.js"></script>
</body>

</html>