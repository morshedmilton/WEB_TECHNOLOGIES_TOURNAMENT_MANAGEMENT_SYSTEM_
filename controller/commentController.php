<?php
require_once '../model/commentModel.php';
require_once '../model/tournamentModel.php';
$user = require_login('../view/');
require_post('tournamentList.php');

$tid = (int) ($_POST['tournament_id'] ?? 0);
$back = '../view/detailsTournament.php?id=' . $tid . '#reviews';
$t = getTournamentById($tid);
if (!$t) {
    redirect('../view/tournamentList.php');
}

if (isset($_POST['postComment'])) {
    $comment = trim($_POST['comment'] ?? '');
    $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
    if ($comment === '') {
        flash('error', 'Please write something before posting.');
    } elseif (hasUserReviewed($tid, $user['id'])) {
        flash('error', 'You have already reviewed this tournament.');
    } else {
        addComment($tid, $user['id'], mb_substr($comment, 0, 1000), $rating);
        logActivity('New review posted on ' . $t['title']);
        flash('success', 'Thanks for your feedback!');
    }
}

if (isset($_POST['deleteComment'])) {
    $cid = (int) ($_POST['comment_id'] ?? 0);
    $row = db_one('SELECT user_id FROM comments WHERE id = ?', [$cid]);
    if ($row && ($user['role'] === 'Admin' || (int) $row['user_id'] === (int) $user['id'])) {
        deleteComment($cid);
        flash('success', 'Review deleted.');
    }
}
redirect($back);
