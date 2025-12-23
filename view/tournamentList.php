<?php
session_start();
// Login check
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}

// Later all tournament list will be fetched from database here
// $tournaments = getAllTournaments(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tournament List - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
    <style>
        /* Special styles for this page's table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }

        .search-panel {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <fieldset style="width: 800px; margin: 50px auto;">
        <legend>Manage Tournaments</legend>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="home.php" style="display: inline;">Dashboard</a> |
            <a href="createTournament.php" style="display: inline;">Create New Tournament</a> |
            <a href="../controller/logout.php" style="display: inline; color: red;">Logout</a>
        </div>

        <div class="search-panel">
            <input type="text" id="search" placeholder="Search by title..." style="width: 40%; display: inline;">
            <select id="filterCategory" style="width: 30%; padding: 8px; display: inline;">
                <option value="">All Categories</option>
                <option value="Cricket">Cricket</option>
                <option value="Football">Football</option>
                <option value="Badminton">Badminton</option>
            </select>
            <button type="button" style="padding: 8px 15px;">Search</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Winter Cricket Cup</td>
                    <td>Cricket</td>
                    <td>admin</td>
                    <td>Upcoming</td>
                    <td>
                        <a href="editTournament.php?id=1" style="display: inline; margin: 0;">Edit</a> |
                        <a href="detailsTournament.php?id=1" style="display: inline; margin: 0;">Details</a> |
                        <a href="../controller/deleteTournament.php?id=1"
                            style="display: inline; margin: 0; color: red;">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>FIFA Summer Blast</td>
                    <td>Football</td>
                    <td>organizer1</td>
                    <td>Ongoing</td>
                    <td>
                        <a href="editTournament.php?id=2" style="display: inline; margin: 0;">Edit</a> |
                        <a href="detailsTournament.php?id=2" style="display: inline; margin: 0;">Details</a> |
                        <a href="../controller/deleteTournament.php?id=2"
                            style="display: inline; margin: 0; color: red;">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            <a href="#" style="display: inline;">&laquo; Previous</a>
            <span style="margin: 0 15px;">Page 1 of 1</span>
            <a href="#" style="display: inline;">Next &raquo;</a>
        </div>

    </fieldset>

</body>

</html>