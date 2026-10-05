<?php
require_once __DIR__ . '/helpers.php';

function createUser($name, $username, $email, $password, $role = 'Player')
{
    return db_insert(
        'INSERT INTO users (name, username, email, password_hash, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$name, $username, $email, password_hash($password, PASSWORD_DEFAULT), $role, 'Active', now()]
    );
}

/** Returns the user row on success, false on bad credentials, null if the account is blocked. */
function authenticate($identifier, $password)
{
    $user = db_one('SELECT * FROM users WHERE username = ? OR email = ?', [$identifier, $identifier]);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }
    if ($user['status'] !== 'Active') {
        return null;
    }
    if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
        db_exec('UPDATE users SET password_hash = ? WHERE id = ?', [password_hash($password, PASSWORD_DEFAULT), $user['id']]);
    }
    return $user;
}

function isUserUnique($username, $email)
{
    return (int) db_val('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?', [$username, $email]) === 0;
}

function emailTakenByOther($email, $userId)
{
    return (int) db_val('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?', [$email, $userId]) > 0;
}

function getUserById($id)
{
    return db_one('SELECT * FROM users WHERE id = ?', [$id]);
}

function getUserByEmail($email)
{
    return db_one('SELECT * FROM users WHERE email = ?', [$email]);
}

function getAllUsers($search = '')
{
    if ($search !== '') {
        $like = '%' . $search . '%';
        return db_all('SELECT * FROM users WHERE name LIKE ? OR username LIKE ? OR email LIKE ? ORDER BY id', [$like, $like, $like]);
    }
    return db_all('SELECT * FROM users ORDER BY id');
}

function updateUserAdmin($id, $role, $status)
{
    return db_exec('UPDATE users SET role = ?, status = ? WHERE id = ?', [$role, $status, $id]);
}

function updateProfile($id, $name, $email)
{
    return db_exec('UPDATE users SET name = ?, email = ? WHERE id = ?', [$name, $email, $id]);
}

function updateProfilePicture($id, $filename)
{
    return db_exec('UPDATE users SET profile_picture = ? WHERE id = ?', [$filename, $id]);
}

function updatePassword($id, $newPassword)
{
    return db_exec('UPDATE users SET password_hash = ? WHERE id = ?', [password_hash($newPassword, PASSWORD_DEFAULT), $id]);
}

function countUsersByRole()
{
    $rows = db_all('SELECT role, COUNT(*) AS total FROM users GROUP BY role');
    $out = ['Admin' => 0, 'Organizer' => 0, 'Player' => 0];
    foreach ($rows as $r) {
        $out[$r['role']] = (int) $r['total'];
    }
    return $out;
}

function saveContactMessage($name, $email, $subject, $message)
{
    return db_insert(
        'INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)',
        [$name, $email, $subject, $message, now()]
    );
}

function getContactMessages()
{
    return db_all('SELECT * FROM contact_messages ORDER BY id DESC LIMIT 50');
}
