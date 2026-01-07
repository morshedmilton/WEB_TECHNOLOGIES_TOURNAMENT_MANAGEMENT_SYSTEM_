<?php
require_once('db.php');

// Create Team [PRD Item 18]
function createTeam($team)
{
    $con = getConnection();
    $sql = "insert into teams (name, members, created_by) values('{$team['name']}', '{$team['members']}', '{$team['created_by']}')";
    $status = mysqli_query($con, $sql);
    mysqli_close($con);
    return $status;
}

// View all teams list
function getAllTeams()
{
    $con = getConnection();
    $sql = "select * from teams";
    $result = mysqli_query($con, $sql);
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($teams, $row);
    }
    mysqli_close($con);
    return $teams;
}

// Join Tournament (Registration)
function joinTournament($tournament_id, $team_id)
{
    $con = getConnection();
    $sql = "insert into tournament_registrations (tournament_id, team_id) values('$tournament_id', '$team_id')";
    $status = mysqli_query($con, $sql);
    mysqli_close($con);
    return $status;
}

// View registered teams for a tournament
function getRegisteredTeams($tournament_id)
{
    $con = getConnection();
    $sql = "select teams.* from teams 
            join tournament_registrations on teams.id = tournament_registrations.team_id 
            where tournament_registrations.tournament_id = '$tournament_id'";
    $result = mysqli_query($con, $sql);
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($teams, $row);
    }
    mysqli_close($con);
    return $teams;
}

// Get list of teams created by user
function getTeamsByCreator($username)
{
    $con = getConnection();
    $sql = "select * from teams where created_by='$username'";
    $result = mysqli_query($con, $sql);
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($teams, $row);
    }
    mysqli_close($con);
    return $teams;
}

// Check if player is registered in system
function isPlayerRegistered($username)
{
    $con = getConnection();
    $sql = "select * from users where username='$username' and role='Player'";
    $result = mysqli_query($con, $sql);
    $status = mysqli_num_rows($result) > 0;
    mysqli_close($con);
    return $status;
}

// ব্যবহারকারীর নিজের সব টিম খুঁজে বের করা (Creator বা Member হিসেবে)
function getMyTeams($username)
{
    $con = getConnection();
    $sql = "SELECT * FROM teams";
    $result = mysqli_query($con, $sql);
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $isMember = false;
        // যদি ইউজার টিমের ক্রিয়েটর হয়
        if ($row['created_by'] == $username) {
            $isMember = true;
        } else {
            // মেম্বার লিস্ট চেক করা (কমা সেপারেটেড স্ট্রিং)
            $members = explode(',', $row['members']);
            foreach ($members as $m) {
                if (trim($m) == $username) {
                    $isMember = true;
                    break;
                }
            }
        }
        if ($isMember) {
            array_push($teams, $row);
        }
    }
    mysqli_close($con);
    return $teams;
}
?>