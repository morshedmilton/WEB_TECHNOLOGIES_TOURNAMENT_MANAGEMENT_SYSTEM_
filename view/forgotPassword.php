<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/forgotPasswordCheck.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Forgot Password</legend>
            <p style="text-align: center; font-size: 13px;">Enter your email to receive a reset link.</p>
            Email: <input type="email" name="email" id="email" value="" /> <br>

            <input type="submit" name="submit" value="Send Reset Link" />
            <a href='login.php'>Back to Login</a>
        </fieldset>
    </form>
</body>

</html>