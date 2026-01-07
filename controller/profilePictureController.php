<?php
session_start();
require_once('../model/userModel.php');

if (isset($_POST['upload'])) {
    // Get user info using username from session (to get user ID)
    $con = getConnection();
    $username = $_SESSION['username'];
    $sql = "SELECT id FROM users WHERE username='$username'";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);
    $id = $row['id'];

    // File handling
    $file = $_FILES['profile_pic'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png'];

    if (in_array($fileExt, $allowed)) {
        if ($fileSize < 5000000) { // 5MB Limit
            $newName = "user_" . $id . "_" . time() . "." . $fileExt;
            $destination = "../uploads/users/" . $newName;

            if (move_uploaded_file($fileTmp, $destination)) {
                // Database update
                if (updateProfilePicture($id, $newName)) {
                    header('location: ../view/profile.php?success=uploaded');
                } else {
                    echo "Database Error!";
                }
            } else {
                echo "Upload Failed!";
            }
        } else {
            echo "File too large!";
        }
    } else {
        echo "Invalid file type!";
    }
} else {
    header('location: ../view/profile.php');
}
?>