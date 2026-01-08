<?php

require_once('db.php');

function getActiveTournamentCount(){
    $con = getConnection();
    $sql = "select count(*) as total from tournaments where status != 'Completed'";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $data['total'];
}

function getTodayActivityCount(){
    $con = getConnection();
    $sql = "SELECT COUNT(*) as total FROM activity_log WHERE DATE(timestamp) = CURDATE()";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $data['total'];
}

function createTournament($tournament){
    $con = getConnection();
    $title = mysqli_real_escape_string($con, $tournament['title']);
    $category = mysqli_real_escape_string($con, $tournament['category']);
    $desc = mysqli_real_escape_string($con, $tournament['description']);
    $banner = mysqli_real_escape_string($con, $tournament['banner_image']);
    $creator = mysqli_real_escape_string($con, $tournament['created_by']);

    $sql = "insert into tournaments (title, category, description, banner_image, status, created_by) 
            values('$title', '$category', '$desc', '$banner', 'Upcoming', '$creator')";

    if (mysqli_query($con, $sql)) {
        $last_id = mysqli_insert_id($con);
        mysqli_close($con);
        return $last_id;
    }
    
    mysqli_close($con);
    return false;
}

function addAttachment($t_id, $name, $path, $type){
    $con = getConnection();
    $t_id = mysqli_real_escape_string($con, $t_id);
    $name = mysqli_real_escape_string($con, $name);
    $path = mysqli_real_escape_string($con, $path);

    $sql = "insert into attachments (tournament_id, file_name, file_path, file_type) values('$t_id', '$name', '$path', '$type')";
    
    $res = mysqli_query($con, $sql);
    mysqli_close($con);
    return $res;
}

function getAttachmentsByTournament($t_id){
    $con = getConnection();
    $t_id = mysqli_real_escape_string($con, $t_id);
    $sql = "select * from attachments where tournament_id = '$t_id'";
    $result = mysqli_query($con, $sql);
    $files = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($files, $row);
    }
    
    mysqli_close($con);
    return $files;
}

function logActivity($text){
    $con = getConnection();
    $safe_text = mysqli_real_escape_string($con, $text);
    $sql = "INSERT INTO activity_log (activity_text) VALUES ('$safe_text')";
    mysqli_query($con, $sql);
    mysqli_close($con);
}

function getTournamentById($id){
    $con = getConnection();
    $id = mysqli_real_escape_string($con, $id);
    $sql = "select * from tournaments where id='$id'";
    $result = mysqli_query($con, $sql);
    $tournament = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $tournament;
}

function getAllTournaments(){
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

function updateTournament($tournament){
    $con = getConnection();
    $id = mysqli_real_escape_string($con, $tournament['id']);
    $title = mysqli_real_escape_string($con, $tournament['title']);
    $category = mysqli_real_escape_string($con, $tournament['category']);
    $desc = mysqli_real_escape_string($con, $tournament['description']);
    $status = mysqli_real_escape_string($con, $tournament['status']);

    $sql = "update tournaments set title='$title', category='$category', description='$desc', status='$status' where id='$id'";

    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return $result;
}

function deleteTournament($id){
    $con = getConnection();
    $id = mysqli_real_escape_string($con, $id);
    $sql = "delete from tournaments where id='$id'";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return $result;
}

function getAllActivities(){
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

function getTournamentsByTeamIDs($teamIds){
    $con = getConnection();
    // teamIds is a comma separated string of numbers, basic cleaning
    $teamIds = mysqli_real_escape_string($con, $teamIds);

    $sql = "SELECT DISTINCT t.* FROM tournaments t JOIN tournament_registrations tr ON t.id = tr.tournament_id WHERE tr.team_id IN ($teamIds) ORDER BY t.id DESC";
    $result = mysqli_query($con, $sql);
    $tournaments = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($tournaments, $row);
    }
    
    mysqli_close($con);
    return $tournaments;
}
?>
