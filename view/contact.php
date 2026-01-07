<!DOCTYPE html>
<html>

<head>
    <title>Contact Admin</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 450px; margin: 50px auto;">
        <legend>Support & Contact</legend>
        <div style="text-align: center;"><a href="home.php">Back to Dashboard</a></div>
        <form>
            Name: <input type="text" placeholder="Your Name"><br>
            Subject: <input type="text" placeholder="Problem Title"><br>
            Message: <textarea rows="4" style="width: 95%;" placeholder="Describe your issue..."></textarea><br>
            <input type="button" value="Submit Message" onclick="alert('Message saved! Support will contact you.')">
        </form>
    </fieldset>
</body>

</html>