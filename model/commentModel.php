<?php
require_once('db.php');

function addComment($tournament_id, $username, $comment, $rating)
{
    $con = getConnection();
    $sql = "insert into comments (tournament_id, username, comment, rating) 
            values('$tournament_id', '$username', '$comment', '$rating')";
    $res = mysqli_query($con, $sql);
    mysqli_close($con);
    return $res;
}

function getCommentsByTournament($tournament_id)
{
    $con = getConnection();
    $sql = "select * from comments where tournament_id = '$tournament_id' order by created_at DESC";
    $result = mysqli_query($con, $sql);
    $comments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($comments, $row);
    }
    mysqli_close($con);
    return $comments;
}
?>