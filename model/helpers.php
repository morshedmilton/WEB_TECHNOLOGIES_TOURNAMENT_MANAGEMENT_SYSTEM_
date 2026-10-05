<?php
/**
 * Shared helpers: session, auth guards, CSRF, flash messages, formatting, uploads.
 */
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/icons.php';

const CATEGORIES = ['Cricket', 'Football', 'Basketball', 'Volleyball', 'Badminton', 'E-Sports'];
const ROLES = ['Admin', 'Organizer', 'Player'];
const TOURNAMENT_STATUSES = ['Upcoming', 'Ongoing', 'Completed'];
const MATCH_STATUSES = ['Scheduled', 'In Progress', 'Finished'];

/* ---------- Session ---------- */

function session_boot()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
if (PHP_SAPI !== 'cli') {
    session_boot();

    // Never leak stack traces or DB details to visitors; log them instead.
    set_exception_handler(function ($e) {
        error_log(get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
        if (!headers_sent()) {
            http_response_code(500);
        }
        $detail = env_value('APP_DEBUG') ? '<pre style="text-align:left;white-space:pre-wrap">' . htmlspecialchars($e->getMessage()) . '</pre>' : '';
        echo '<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Something went wrong</title>'
            . '<body style="font-family:system-ui,sans-serif;display:grid;place-items:center;min-height:100vh;margin:0;background:#0a0f1f;color:#e8ecf8;text-align:center">'
            . '<div style="max-width:440px;padding:24px"><div style="font-size:3rem;color:#818cf8">' . icon('wrench', 52) . '</div><h1>Something went wrong</h1>'
            . '<p style="color:#93a0bd">We hit an unexpected error. Please try again in a moment.</p>' . $detail
            . '<a href="javascript:history.back()" style="color:#818cf8">← Go back</a></div></body>';
        exit(1);
    });
}

/* ---------- Output / navigation ---------- */

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit();
}

function flash($type, $message)
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_pull()
{
    $messages = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
    unset($_SESSION['flash']);
    return $messages;
}

/* ---------- CSRF ---------- */

function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Controllers call this first: only POST + valid token gets through. */
function require_post($back = 'home.php')
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('../view/' . $back);
    }
    $sent = isset($_POST['csrf']) ? $_POST['csrf'] : '';
    if (!hash_equals(csrf_token(), $sent)) {
        flash('error', 'Your session expired. Please try again.');
        redirect('../view/' . $back);
    }
}

/* ---------- Auth ---------- */

function current_user()
{
    static $user = false;
    if ($user !== false) {
        return $user;
    }
    $user = null;
    if (!empty($_SESSION['user_id'])) {
        $user = db_one('SELECT id, name, username, email, role, status, profile_picture FROM users WHERE id = ?', [$_SESSION['user_id']]);
        if (!$user || $user['status'] !== 'Active') {
            $user = null;
            unset($_SESSION['user_id']);
        }
    }
    return $user;
}

function is_logged_in()
{
    return current_user() !== null;
}

function require_login($prefix = '')
{
    if (!is_logged_in()) {
        flash('error', 'Please sign in to continue.');
        redirect($prefix . 'login.php');
    }
    return current_user();
}

function has_role($roles)
{
    $user = current_user();
    return $user && in_array($user['role'], (array) $roles, true);
}

function require_role($roles, $prefix = '')
{
    $user = require_login($prefix);
    if (!in_array($user['role'], (array) $roles, true)) {
        flash('error', 'You do not have permission to do that.');
        redirect($prefix . 'home.php');
    }
    return $user;
}

function can_manage_tournament($tournament)
{
    $user = current_user();
    return $user && $tournament && ($user['role'] === 'Admin' || (int) $user['id'] === (int) $tournament['created_by']);
}

/* ---------- Presentation helpers ---------- */

function category_meta($category)
{
    $map = [
        'Cricket' => ['icon' => 'cricket', 'a' => '#16a34a', 'b' => '#065f46'],
        'Football' => ['icon' => 'football', 'a' => '#2563eb', 'b' => '#1e1b4b'],
        'Basketball' => ['icon' => 'basketball', 'a' => '#f97316', 'b' => '#9a3412'],
        'Volleyball' => ['icon' => 'volleyball', 'a' => '#eab308', 'b' => '#a16207'],
        'Badminton' => ['icon' => 'badminton', 'a' => '#06b6d4', 'b' => '#155e75'],
        'E-Sports' => ['icon' => 'gamepad', 'a' => '#a855f7', 'b' => '#4c1d95'],
    ];
    return isset($map[$category]) ? $map[$category] : ['icon' => 'trophy', 'a' => '#6366f1', 'b' => '#312e81'];
}

function banner_style($category)
{
    $m = category_meta($category);
    return 'background: linear-gradient(135deg, ' . $m['a'] . ', ' . $m['b'] . ');';
}

function badge_class($status)
{
    $map = [
        'Upcoming' => 'info', 'Ongoing' => 'live', 'Completed' => 'muted',
        'Scheduled' => 'info', 'In Progress' => 'live', 'Finished' => 'muted',
        'Active' => 'success', 'Blocked' => 'danger',
        'Admin' => 'danger', 'Organizer' => 'warn', 'Player' => 'info',
    ];
    return isset($map[$status]) ? $map[$status] : 'muted';
}

function fmt_date($datetime, $format = 'M j, Y')
{
    $ts = strtotime($datetime);
    return $ts ? date($format, $ts) : '—';
}

function time_ago($datetime)
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60) {
        return 'just now';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . ' min ago';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . ' hr ago';
    }
    if ($diff < 86400 * 7) {
        return floor($diff / 86400) . ' d ago';
    }
    return fmt_date($datetime);
}

function initials($name)
{
    $parts = preg_split('/\s+/', trim($name));
    $out = strtoupper(substr($parts[0], 0, 1));
    if (count($parts) > 1) {
        $out .= strtoupper(substr(end($parts), 0, 1));
    }
    return $out;
}

/** Avatar: uploaded picture if present, otherwise a colored initials bubble. */
function avatar_html($name, $picture = null, $size = 36)
{
    $style = 'width:' . (int) $size . 'px;height:' . (int) $size . 'px;font-size:' . round($size * 0.38) . 'px;';
    if ($picture && $picture !== 'default_user.png' && is_file(__DIR__ . '/../uploads/users/' . $picture)) {
        return '<img class="avatar" style="' . $style . '" src="../uploads/users/' . e($picture) . '" alt="' . e($name) . '">';
    }
    $hue = abs(crc32($name)) % 360;
    return '<span class="avatar" style="' . $style . 'background:hsl(' . $hue . ' 65% 45%)">' . e(initials($name)) . '</span>';
}

function stars_html($rating)
{
    $rating = max(0, min(5, (int) round($rating)));
    return '<span class="stars" aria-label="' . $rating . ' out of 5">' . str_repeat('★', $rating) . '<span class="off">' . str_repeat('★', 5 - $rating) . '</span></span>';
}

/* ---------- Uploads ---------- */

/**
 * Validate and store an uploaded file. Returns the stored file name or null.
 * $kind: 'image' (jpg/png/webp) or 'document' (pdf/doc/docx).
 */
function save_upload($file, $dir, $kind, $prefix, &$error = null)
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed. Please try again.';
        return null;
    }
    $rules = [
        'image' => ['ext' => ['jpg', 'jpeg', 'png', 'webp'], 'mime' => ['image/jpeg', 'image/png', 'image/webp'], 'max' => 5 * 1024 * 1024],
        'document' => ['ext' => ['pdf', 'doc', 'docx'], 'mime' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/octet-stream', 'application/zip'], 'max' => 10 * 1024 * 1024],
    ];
    $rule = $rules[$kind];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $rule['ext'], true)) {
        $error = 'Unsupported file type. Allowed: ' . implode(', ', $rule['ext']) . '.';
        return null;
    }
    if ($file['size'] > $rule['max']) {
        $error = 'File is too large (max ' . round($rule['max'] / 1048576) . ' MB).';
        return null;
    }
    $mime = function_exists('mime_content_type') ? mime_content_type($file['tmp_name']) : $rule['mime'][0];
    if (!in_array($mime, $rule['mime'], true)) {
        $error = 'File content does not match its extension.';
        return null;
    }
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $name = $prefix . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], rtrim($dir, '/') . '/' . $name)) {
        $error = 'Could not save the uploaded file.';
        return null;
    }
    return $name;
}
