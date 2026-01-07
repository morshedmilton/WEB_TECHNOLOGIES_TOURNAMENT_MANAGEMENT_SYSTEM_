<?php
require_once('db.php');

function createTeam($team)
{
    $con = getConnection();
    $sql = "insert into teams (name, members, created_by) values('{$team['name']}', '{$team['members']}', '{$team['created_by']}')";
    $status = mysqli_query($con, $sql);
    mysqli_close($con);
    return $status;
}

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

function joinTournament($tournament_id, $team_id)
{
    $con = getConnection();
    $sql = "insert into tournament_registrations (tournament_id, team_id) values('$tournament_id', '$team_id')";
    $status = mysqli_query($con, $sql);
    mysqli_close($con);
    return $status;
}

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

function isPlayerRegistered($username)
{
    $con = getConnection();
    $sql = "select * from users where username='$username' and role='Player'";
    $result = mysqli_query($con, $sql);
    $status = mysqli_num_rows($result) > 0;
    mysqli_close($con);
    return $status;
}

function getMyTeams($username)
{
    $con = getConnection();
    $sql = "SELECT * FROM teams";
    $result = mysqli_query($con, $sql);
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $isMember = false;
        if ($row['created_by'] == $username) {
            $isMember = true;
        } else {
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