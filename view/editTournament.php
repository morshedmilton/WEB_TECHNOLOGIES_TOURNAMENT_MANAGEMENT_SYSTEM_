<?php
session_start();
// Login check: if status cookie is not present, redirect to login page
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}

// Collect tournament ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Later tournament info for this ID will be fetched from database here
    // $tournament = getTournamentById($id); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Tournament - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>

    <form method="post" action="../controller/tournamentController.php" enctype="multipart/form-data"
        onsubmit="return validateTournament()">
        <fieldset>
            <legend>Edit Tournament Details</legend>

            <div style="text-align: center; margin-bottom: 20px;">
                <a href="home.php" style="display: inline;">Dashboard</a> |
                <a href="tournamentList.php" style="display: inline;">Back to List</a>
            </div>

            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">

            Tournament Title: <input type="text" name="title" id="title" value="Sample Tournament Title" /> <br>

            Category:
            <select name="category" id="category"
                style="width: 95%; padding: 8px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;">
                <option value="Cricket" selected>Cricket</option>
                <option value="Football">Football</option>
                <option value="Badminton">Badminton</option>
                <option value="E-Sports">E-Sports</option>
            </select> <br>

            Description: <textarea name="content" id="content" rows="5"
                style="width: 95%; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px;">This is the existing description of the tournament.</textarea>
            <br>

            Tags: <input type="text" name="tags" id="tags" value="summer, cricket, 2024" /> <br>

            Current Banner: <br>
            <img src="../asset/upload/sample.jpg" alt="Banner"
                style="width: 100px; margin: 10px 0; border: 1px solid #000;"> <br>

            Change Banner: <input type="file" name="attachment" id="attachment" style="margin-top: 10px;" /> <br><br>

            <input type="submit" name="update" value="Update Tournament" />

            <p id="jsError" style="color: red; text-align: center;"></p>
        </fieldset>
    </form>

    <script src="../asset/js/validation.js"></script>
</body>

</html>