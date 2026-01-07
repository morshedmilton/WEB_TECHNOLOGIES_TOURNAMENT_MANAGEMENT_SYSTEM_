<?php
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    // [FIX] সব ধরণের এরর হ্যান্ডলিং যোগ করা হয়েছে
    if ($error == "db_error") {
        $err1 = "Registration failed! Database error.";
    } elseif ($error == "mismatch") {
        $err1 = "Passwords do not match!";
    } elseif ($error == "not_unique") {
        $err1 = "Username or Email already exists!";
    } elseif ($error == "null") {
        $err1 = "Please fill all fields!";
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
            Name: <input type="text" name="name" id="name" value="" autocomplete="name" /> <br>
            Username: <input type="text" name="username" id="username" value="" autocomplete="username" /> <br>
            Email: <input type="email" name="email" id="email" value="" autocomplete="email" /> <br>
            Password: <input type="password" name="password" id="password" value="" autocomplete="new-password" /> <br>
            Confirm Password: <input type="password" name="confirmPassword" id="confirmPassword" value=""
                autocomplete="new-password" /> <br>

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