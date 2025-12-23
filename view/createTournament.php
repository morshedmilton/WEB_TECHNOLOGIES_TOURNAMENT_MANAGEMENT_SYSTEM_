<?php
session_start();
// Login check: if status cookie is not present, redirect to login page
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Tournament - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>

    <form method="post" action="../controller/tournamentController.php" enctype="multipart/form-data"
        onsubmit="return validateTournament()">
        <fieldset>
            <legend>Create New Tournament</legend>

            <div style="text-align: center; margin-bottom: 20px;">
                <a href="home.php" style="display: inline;">Dashboard</a> |
                <a href="tournamentList.php" style="display: inline;">Tournament List</a>
            </div>

            Tournament Title: <input type="text" name="title" id="title" value="" /> <br>

            Category:
            <select name="category" id="category"
                style="width: 95%; padding: 8px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">Select Category</option>
                <option value="Cricket">Cricket</option>
                <option value="Football">Football</option>
                <option value="Badminton">Badminton</option>
                <option value="E-Sports">E-Sports</option>
            </select> <br>

            Description: <textarea name="content" id="content" rows="5"
                style="width: 95%; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea> <br>

            Tags (comma separated): <input type="text" name="tags" id="tags" placeholder="e.g. summer, 2024, knockout"
                value="" /> <br>

            Banner/Attachment: <input type="file" name="attachment" id="attachment" style="margin-top: 10px;" />
            <br><br>

            <input type="submit" name="submit" value="Save Tournament" />

            <p id="jsError" style="color: red; text-align: center;"></p>
        </fieldset>
    </form>

    <script src="../asset/js/validation.js"></script>
</body>

</html>