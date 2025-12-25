<?php
require_once('db.php');

// 1. Get count of active tournaments [PRD Item 5 - Real-time]
function getActiveTournamentCount()
{
    $con = getConnection();
    $sql = "select count(*) as total from tournaments where status != 'Completed'";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $data['total'];
}

// 2. Get today's activity count [Corrected Query]
function getTodayActivityCount()
{
    $con = getConnection();
    // Using DATE(timestamp) will match directly with today's date
    $sql = "SELECT COUNT(*) as total FROM activity_log WHERE DATE(timestamp) = CURDATE()";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $data['total'];
}

// 3. Log new activity
function logActivity($text)
{
    $con = getConnection();
    // Using mysqli_real_escape_string is safe to prevent SQL injection related to the text
    $safe_text = mysqli_real_escape_string($con, $text);
    $sql = "INSERT INTO activity_log (activity_text) VALUES ('$safe_text')";
    mysqli_query($con, $sql);
    mysqli_close($con);
}

function createTournament($tournament)
{
    $con = getConnection();
    $sql = "insert into tournaments (title, category, description, status, created_by) 
            values('{$tournament['title']}', '{$tournament['category']}', '{$tournament['description']}', 'Upcoming', '{$tournament['created_by']}')";
    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
}

// 4. Get list of all tournaments [PRD Item 14]
function getAllTournaments()
{
    $con = getConnection();
    $sql = "select * from tournaments order by id desc";
    $result = mysqli_query($con, $sql);
    $tournaments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($tournaments, $row);
    }
    mysqli_close($con);
    return $tournaments;
}

// 5. Get a specific tournament by ID [PRD Item 15]
function getTournamentById($id)
{
    $con = getConnection();
    $sql = "select * from tournaments where id='{$id}'";
    $result = mysqli_query($con, $sql);
    $tournament = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $tournament;
}

// 6. Update tournament information [PRD Item 15]
function updateTournament($tournament)
{
    $con = getConnection();
    $sql = "update tournaments set title='{$tournament['title']}', 
            category='{$tournament['category']}', 
            description='{$tournament['description']}', 
            status='{$tournament['status']}' 
            where id='{$tournament['id']}'";

    if (mysqli_query($con, $sql)) {
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

// 7. Delete tournament [PRD Item 16]
function deleteTournament($id)
{
    $con = getConnection();
    $sql = "delete from tournaments where id='{$id}'";
    if (mysqli_query($con, $sql)) {
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

// Get all activity logs [PRD Item 35]
function getAllActivities()
{
    $con = getConnection();
    $sql = "SELECT * FROM activity_log ORDER BY timestamp DESC";
    $result = mysqli_query($con, $sql);
    $logs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($logs, $row);
    }
    mysqli_close($con);
    return $logs;
}
?>