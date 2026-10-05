<?php
require_once __DIR__ . '/helpers.php';

function addComment($tournamentId, $userId, $comment, $rating)
{
    return db_insert(
        'INSERT INTO comments (tournament_id, user_id, comment, rating, created_at) VALUES (?, ?, ?, ?, ?)',
        [$tournamentId, $userId, $comment, $rating, now()]
    );
}

function getCommentsByTournament($tournamentId)
{
    return db_all(
        'SELECT c.*, u.name, u.username, u.profile_picture FROM comments c JOIN users u ON u.id = c.user_id WHERE c.tournament_id = ? ORDER BY c.id DESC',
        [$tournamentId]
    );
}

function hasUserReviewed($tournamentId, $userId)
{
    return (int) db_val('SELECT COUNT(*) FROM comments WHERE tournament_id = ? AND user_id = ?', [$tournamentId, $userId]) > 0;
}

function deleteComment($id)
{
    return db_exec('DELETE FROM comments WHERE id = ?', [$id]);
}

/** Rating histogram: [5 => n, 4 => n, ...] */
function getRatingBreakdown($tournamentId)
{
    $out = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    foreach (db_all('SELECT rating, COUNT(*) AS total FROM comments WHERE tournament_id = ? GROUP BY rating', [$tournamentId]) as $r) {
        $out[(int) $r['rating']] = (int) $r['total'];
    }
    return $out;
}
