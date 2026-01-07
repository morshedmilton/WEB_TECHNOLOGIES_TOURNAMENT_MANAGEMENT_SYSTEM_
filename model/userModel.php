<?php
require_once('db.php');

function signup($user)
{
    $con = getConnection();
    $name = mysqli_real_escape_string($con, $user['name']);
    $username = mysqli_real_escape_string($con, $user['username']);
    $email = mysqli_real_escape_string($con, $user['email']);
    $password = mysqli_real_escape_string($con, $user['password']);

    $sql = "insert into users (name, username, email, password, role, status) 
            values('$name', '$username', '$email', '$password', 'Player', 'Active')";

    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return $result;
}

function login($username, $password)
{
    $con = getConnection();
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);

    $sql = "select * from users where username='$username' and password='$password' and status='Active'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $user = mysqli_fetch_assoc($result);
        mysqli_close($con);
        return $user;
    } else {
        mysqli_close($con);
        return false;
    }
}

function isUnique($username, $email)
{
    $con = getConnection();
    $username = mysqli_real_escape_string($con, $username);
    $email = mysqli_real_escape_string($con, $email);

    $sql = "select * from users where username='$username' or email='$email'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);
    mysqli_close($con);
    return $count == 0;
}

function getUserByUsername($username)
{
    $con = getConnection();
    $username = mysqli_real_escape_string($con, $username);
    $sql = "select * from users where username='$username'";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $user;
}

function getAllUsers()
{
    $con = getConnection();
    $sql = "select * from users";
    $result = mysqli_query($con, $sql);
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($users, $row);
    }
    mysqli_close($con);
    return $users;
}

function updateUserAdmin($user)
{
    $con = getConnection();
    $id = mysqli_real_escape_string($con, $user['id']);
    $role = mysqli_real_escape_string($con, $user['role']);
    $status = mysqli_real_escape_string($con, $user['status']);

    $sql = "update users set role='$role', status='$status' where id='$id'";
    $status = mysqli_query($con, $sql);
    mysqli_close($con);
    return $status;
}

function getUserById($id)
{
    $con = getConnection();
    $id = mysqli_real_escape_string($con, $id);
    $sql = "select * from users where id='$id'";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $user;
}

function updateProfilePicture($id, $filename)
{
    $con = getConnection();
    $id = mysqli_real_escape_string($con, $id);
    $filename = mysqli_real_escape_string($con, $filename);
    $sql = "UPDATE users SET profile_picture='$filename' WHERE id='$id'";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return $result;
}

function updatePassword($id, $newPassword)
{
    $con = getConnection();
    $id = mysqli_real_escape_string($con, $id);
    $newPassword = mysqli_real_escape_string($con, $newPassword);
    $sql = "UPDATE users SET password='$newPassword' WHERE id='$id'";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return $result;
}
?>