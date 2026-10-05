<?php
require_once __DIR__ . '/helpers.php';

function logActivity($text, $userId = null)
{
    if ($userId === null) {
        $user = current_user();
        $userId = $user ? $user['id'] : null;
    }
    db_exec('INSERT INTO activity_log (user_id, activity_text, created_at) VALUES (?, ?, ?)', [$userId, $text, now()]);
}

function getRecentActivities($limit = 100)
{
    return db_all(
        'SELECT a.*, u.username, u.name AS user_name, u.profile_picture
         FROM activity_log a LEFT JOIN users u ON u.id = a.user_id
         ORDER BY a.id DESC LIMIT ' . (int) $limit
    );
}

function getTodayActivityCount()
{
    return (int) db_val('SELECT COUNT(*) FROM activity_log WHERE created_at >= ?', [date('Y-m-d 00:00:00')]);
}

/** Activity per day for the last $days days (oldest first), for the reports chart. */
function getActivityByDay($days = 7)
{
    $out = [];
    for ($i = $days - 1; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-$i days"));
        $count = (int) db_val('SELECT COUNT(*) FROM activity_log WHERE created_at >= ? AND created_at <= ?', [$day . ' 00:00:00', $day . ' 23:59:59']);
        $out[] = ['label' => date('D', strtotime($day)), 'value' => $count];
    }
    return $out;
}
