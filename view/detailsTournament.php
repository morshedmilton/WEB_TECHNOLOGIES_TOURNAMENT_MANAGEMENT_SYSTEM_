<?php
session_start();
// Login and cookie check
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}

// Collect ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tournament Details - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>

    <fieldset style="width: 650px; margin: 50px auto;">
        <legend>Tournament Full Details</legend>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="tournamentList.php" style="display: inline;">Back to List</a> |
            <a href="home.php" style="display: inline;">Dashboard</a>
        </div>

        <div style="padding: 10px;">
            <h2 style="text-align: center; color: #333;">Winter Cricket Cup 2024</h2>
            <hr>
            <p><strong>Category:</strong> Cricket</p>
            <p><strong>Organizer:</strong> admin</p>
            <p><strong>Description:</strong> This is a knockout cricket tournament for the local clubs. All matches will
                be held in the city stadium.</p>
            <p><strong>Tags:</strong> <span style="color: blue;">#summer, #cricket, #2024</span></p>

            <div style="text-align: center; margin: 20px 0;">
                <p><strong>Tournament Banner:</strong></p>
                <img src="../asset/upload/banner.jpg" alt="Tournament Banner"
                    style="max-width: 100%; border: 1px solid #000;">
            </div>
        </div>

        <hr>

        <div style="text-align: center; padding: 10px;">
            <strong>User Rating:</strong>
            <span style="color: orange; font-size: 20px;">⭐⭐⭐⭐☆</span> (4.5/5)
        </div>

        <hr>

        <div style="padding: 10px;">
            <h3>Comments & Feedback</h3>

            <div style="border-bottom: 1px solid #eee; padding: 10px 0; margin-bottom: 10px;">
                <strong>User123:</strong> <span style="font-size: 14px;">"Can't wait for the match! Great
                    initiative."</span> <br>
                <small style="color: gray;">Posted on: 2024-05-20 10:30 AM</small>
            </div>

            <form method="post" action="../controller/commentController.php">
                <textarea name="comment" id="comment" placeholder="Write a comment..." rows="3"
                    style="width: 98%; padding: 5px; border-radius: 4px; border: 1px solid #ccc;"></textarea> <br>
                <input type="submit" name="postComment" value="Post Comment"
                    style="width: auto; padding: 8px 20px; margin-top: 10px;">
            </form>
        </div>

    </fieldset>

</body>

</html>