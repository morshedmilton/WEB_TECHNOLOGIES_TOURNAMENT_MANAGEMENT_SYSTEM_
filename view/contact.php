<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Support - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>

    <form method="post" action="../controller/contactCheck.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Contact Us / Support</legend>

            <div style="text-align: center; margin-bottom: 20px;">
                <a href="home.php" style="display: inline;">Dashboard</a> |
                <a href="faq.php" style="display: inline;">FAQ</a>
            </div>

            <p style="text-align: center; font-size: 13px;">Have a question? Send us a message.</p>

            Name: <input type="text" name="name" id="name" value="" /> <br>
            Email: <input type="email" name="email" id="email" value="" /> <br>
            Subject: <input type="text" name="subject" id="subject" value="" /> <br>

            Message:
            <textarea name="message" id="message" rows="5"
                style="width: 95%; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; padding: 8px;"></textarea>
            <br>

            <input type="submit" name="submit" value="Send Message" />

            <p id="jsError" style="color: red; text-align: center;"></p>
        </fieldset>
    </form>

    <script src="../asset/js/validation.js"></script>
</body>

</html>
