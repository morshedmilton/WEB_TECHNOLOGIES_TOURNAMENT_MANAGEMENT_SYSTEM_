<?php
require_once('db.php');

// Create New User (Sign-up) [PRD Item 1]
function signup($user)
{
    $con = getConnection();
    $sql = "insert into users (name, username, email, password, role, status) 
                values('{$user['name']}', '{$user['username']}', '{$user['email']}', '{$user['password']}', 'Player', 'Active')";

    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
}

// User Verification (Sign-in) [PRD Item 2]
function login($username, $password)
{
    $con = getConnection();
    $sql = "select * from users where username='{$username}' and password='{$password}' and status='Active'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $user = mysqli_fetch_assoc($result);
        return $user;
    } else {
        return false;
    }
}

// Check if email and username are unique
function isUnique($username, $email)
{
    $con = getConnection();
    $sql = "select * from users where username='{$username}' or email='{$email}'";
    $result = mysqli_query($con, $sql);
    return mysqli_num_rows($result) == 0;
}

function getUserByUsername($username)
{
    $con = getConnection();
    $sql = "select * from users where username='{$username}'";
    $result = mysqli_query($con, $sql);
    return mysqli_fetch_assoc($result);
}

// Get list of all users [PRD Item 10]
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

// Update user role and status [PRD Item 11]
function updateUserAdmin($user)
{
    $con = getConnection();
    $sql = "update users set role='{$user['role']}', status='{$user['status']}' where id='{$user['id']}'";
    $status = mysqli_query($con, $sql);
    mysqli_close($con);
    return $status;
}

// Get user data by ID
function getUserById($id)
{
    $con = getConnection();
    $sql = "select * from users where id='{$id}'";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $user;
}
?>