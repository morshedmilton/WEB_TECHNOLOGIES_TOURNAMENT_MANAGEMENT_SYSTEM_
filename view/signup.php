<?php
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    if ($error == "regerror") {
        $err1 = "Registration failed! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/signupCheck.php" enctype="multipart/form-data"
        onsubmit="return validateSignup()">
        <fieldset>
            <legend>Registration [Guest]</legend>
            Name: <input type="text" name="name" id="name" value="" /> <br>
            Username: <input type="text" name="username" id="username" value="" /> <br>
            Email: <input type="email" name="email" id="email" value="" /> <br>
            Password: <input type="password" name="password" id="password" value="" /> <br>
            Confirm Password: <input type="password" name="confirmPassword" id="confirmPassword" value="" /> <br>

            <input type="submit" name="submit" value="Sign Up" />
            <a href='login.php'>Sign In</a>
        </fieldset>
    </form>

    <p id="jsError" style="color: red;"></p>

    <p><?php if (isset($err1)) {
        echo $err1;
    } ?> </p>

    <script src="../asset/js/validation.js"></script>
</body>

</html>