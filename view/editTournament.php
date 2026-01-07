<?php
session_start();
require_once('../model/tournamentModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: login.php');
    exit();
}
if (isset($_GET['id'])) {
    $tournament = getTournamentById($_GET['id']);
    $currentUser = $_SESSION['username'];
    $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'Player';
    if ($userRole != 'Admin' && $currentUser != $tournament['created_by']) {
        echo "<script>alert('Access Denied!'); window.location.href='tournamentList.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Tournament</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/tournamentController.php">
        <fieldset>
            <legend>Edit Tournament Details</legend>
            <div style="text-align: center;"><a href="tournamentList.php">Back to List</a></div>
            <input type="hidden" name="id" value="<?php echo $tournament['id']; ?>">
            Title: <input type="text" name="title" value="<?php echo $tournament['title']; ?>"> <br>
            Category:
            <select name="category">
                <option value="Cricket" <?php if ($tournament['category'] == 'Cricket')
                    echo 'selected'; ?>>Cricket
                </option>
                <option value="Football" <?php if ($tournament['category'] == 'Football')
                    echo 'selected'; ?>>Football
                </option>
                <option value="E-Sports" <?php if ($tournament['category'] == 'E-Sports')
                    echo 'selected'; ?>>E-Sports
                </option>
            </select> <br>
            Status:
            <select name="status">
                <option value="Upcoming" <?php if ($tournament['status'] == 'Upcoming')
                    echo 'selected'; ?>>Upcoming
                </option>
                <option value="Ongoing" <?php if ($tournament['status'] == 'Ongoing')
                    echo 'selected'; ?>>Ongoing</option>
                <option value="Completed" <?php if ($tournament['status'] == 'Completed')
                    echo 'selected'; ?>>Completed
                </option>
            </select> <br>
            Description: <br>
            <textarea name="description" rows="5"
                style="width: 95%;"><?php echo $tournament['description']; ?></textarea> <br>
            <input type="submit" name="update" value="Update Tournament">
        </fieldset>
    </form>
</body>

</html>