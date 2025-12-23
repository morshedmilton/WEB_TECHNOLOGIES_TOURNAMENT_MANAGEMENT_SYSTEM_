<?php
// Error message handling from session
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    if ($error == "invalid_user") {
        $err1 = "Invalid username or password!";
    } elseif ($error == "badrequest") {
        $err2 = "Please login first!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form method="post" action="../controller/loginCheck.php" enctype="multipart/form-data"
        onsubmit="return validateLogin()">
        <fieldset>
            <legend>Signin [User/Admin]</legend>
            Username/Email: <input type="text" name="username" id="username" value="" /> <br>
            Password: <input type="password" name="password" id="password" value="" /> <br>

            <input type="checkbox" name="rememberMe"> Remember Me <br><br>

            <input type="submit" name="submit" value="Sign In" />

            <a href='forgotPassword.php'>Forgot Password?</a>
            <a href='signup.php'>Don't have an account? Signup</a>
        </fieldset>
    </form>

    <p id="jsError" style="color: red; text-align: center;"></p>
    <p style="color: red; text-align: center;"><?php if (isset($err1)) {
        echo $err1;
    } ?> </p>
    <p style="color: red; text-align: center;"><?php if (isset($err2)) {
        echo $err2;
    } ?> </p>

    <script src="../asset/js/validation.js"></script>
</body>

</html>