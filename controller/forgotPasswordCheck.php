<?php
require_once('../model/db.php');
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    echo "<script>alert('Simulated: Reset link sent to $email'); window.location.href='../view/login.php';</script>";
}
?>