<?php
require_once('db.php');

function scheduleMatch($match)
{
    $con = getConnection();
    $sql = "insert into matches (tournament_id, team1_id, team2_id, match_date) 
            values('{$match['tournament_id']}', '{$match['team1_id']}', '{$match['team2_id']}', '{$match['match_date']}')";
    $status = mysqli_query($con, $sql);
    mysqli_close($con);
    return $status;
}

// List of matches including winner's name [Fixing ID to Name Issue]
function getMatchesByTournament($tournament_id)
{
    $con = getConnection();
    $sql = "select m.*, t1.name as team1_name, t2.name as team2_name, tw.name as winner_name 
            from matches m 
            join teams t1 on m.team1_id = t1.id 
            join teams t2 on m.team2_id = t2.id 
            left join teams tw on m.winner_id = tw.id 
            where m.tournament_id = '$tournament_id' 
            order by m.match_date ASC";
    $result = mysqli_query($con, $sql);
    $matches = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($matches, $row);
    }
    mysqli_close($con);
    return $matches;
}

function updateMatchResult($match_id, $winner_id, $status)
{
    $con = getConnection();
    $sql = "update matches set winner_id = '$winner_id', status = '$status' where id = '$match_id'";
    $res = mysqli_query($con, $sql);
    mysqli_close($con);
    return $res;
}
?>