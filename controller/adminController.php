<?php
require_once '../model/userModel.php';
require_once '../model/activityModel.php';
$admin = require_role('Admin', '../view/');
require_post('allUser.php');

$id = (int) ($_POST['id'] ?? 0);
$role = in_array($_POST['role'] ?? '', ROLES, true) ? $_POST['role'] : null;
$status = in_array($_POST['status'] ?? '', ['Active', 'Blocked'], true) ? $_POST['status'] : null;
$target = getUserById($id);

if (!$target || !$role || !$status) {
    flash('error', 'Invalid request.');
} elseif ($id === (int) $admin['id'] && ($role !== 'Admin' || $status !== 'Active')) {
    flash('error', "You can't demote or block your own account.");
} else {
    updateUserAdmin($id, $role, $status);
    logActivity("Admin updated {$target['username']} (Role: $role, Status: $status)");
    flash('success', 'User updated.');
}
redirect('../view/allUser.php');
