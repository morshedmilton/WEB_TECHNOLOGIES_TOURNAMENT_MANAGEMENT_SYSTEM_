<?php
session_start();
// Later user model will be required here
// require_once('../model/userModel.php');

// Login and cookie check
if (!isset($_COOKIE['status'])) {
    header('location: login.php?error=badrequest');
}

// Later all users will be fetched from database
// $users = getAllUser(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Management - Tournament Management System</title>
    <link rel="stylesheet" href="../asset/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <fieldset style="width: 850px; margin: 50px auto;">
        <legend>System User Management [Admin]</legend>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="home.php" style="display: inline;">Dashboard</a> |
            <a href="../controller/logout.php" style="display: inline; color: red;">Logout</a>
        </div>

        <div style="text-align: center; margin-bottom: 15px;">
            <input type="text" placeholder="Search by name or email..." style="width: 50%; display: inline;">
            <button type="button" style="padding: 7px 15px;">Search</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Alamin Hossain</td>
                    <td>alamin@aiub.edu</td>
                    <td>Admin</td>
                    <td>Active</td>
                    <td>
                        <a href="editUser.php?id=1" style="display: inline; margin: 0;">EDIT</a> |
                        <a href="deleteUser.php?id=1" style="display: inline; margin: 0; color: red;">BLOCK</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Tournament Organizer</td>
                    <td>org@example.com</td>
                    <td>User</td>
                    <td>Active</td>
                    <td>
                        <a href="editUser.php?id=2" style="display: inline; margin: 0;">EDIT</a> |
                        <a href="deleteUser.php?id=2" style="display: inline; margin: 0; color: red;">BLOCK</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            <a href="#" style="display: inline;">Previous</a> 1 <a href="#" style="display: inline;">Next</a>
        </div>

    </fieldset>

</body>

</html>