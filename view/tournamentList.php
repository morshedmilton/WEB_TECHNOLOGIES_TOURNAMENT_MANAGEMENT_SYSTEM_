<?php
session_start();
require_once('../model/tournamentModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: login.php');
    exit();
}
$tournaments = getAllTournaments();
$currentUser = $_SESSION['username'];
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'Player';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tournament List</title>
    <link rel="stylesheet" href="../asset/css/style.css">
    <script src="../asset/js/ajax.js"></script>
    <script src="../asset/js/validation.js"></script>
</head>

<body>
    <fieldset style="width: 950px; margin: 30px auto;">
        <legend>Tournaments</legend>
        <div style="text-align: center;"><a href="home.php">Dashboard</a> | <a href="createTournament.php">Create
                New</a></div>
        <hr>
        <div style="text-align: center; margin-bottom: 20px;">
            <input type="text" id="search_box" placeholder="Live Search Tournament by Name..."
                onkeyup="searchTournament()" style="width: 60%; padding: 10px; border: 2px solid #000;">
        </div>
        <div id="search_results"></div>
        <table id="main_tournament_table" border="1" cellspacing="0" cellpadding="10"
            style="width: 100%; text-align: center;">
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Banner</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($tournaments as $t): ?>
                <tr>
                    <td><?php echo $t['id']; ?></td>
                    <td><?php if (!empty($t['banner_image'])): ?>
                            <img src="../uploads/banners/<?php echo $t['banner_image']; ?>" width="80" height="50">
                        <?php else: ?> No Banner <?php endif; ?>
                    </td>
                    <td><?php echo $t['title']; ?></td>
                    <td><?php echo $t['category']; ?></td>
                    <td><?php echo $t['status']; ?></td>
                    <td><?php echo $t['created_by']; ?></td>
                    <td>
                        <a href="detailsTournament.php?id=<?php echo $t['id']; ?>">View</a>
                        <?php if ($userRole == 'Admin' || $currentUser == $t['created_by']) { ?>
                            | <a href="editTournament.php?id=<?php echo $t['id']; ?>">Edit</a> |
                            <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $t['id']; ?>)"
                                style="color: red;">Delete</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </fieldset>
</body>

</html>