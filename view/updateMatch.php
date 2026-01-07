<?php
session_start();
require_once('../model/matchModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: login.php');
    exit();
}

if (isset($_GET['match_id'])) {
    $match_id = $_GET['match_id'];
    $con = getConnection();
    $sql = "select m.*, t1.name as t1n, t2.name as t2n from matches m 
                join teams t1 on m.team1_id = t1.id 
                join teams t2 on m.team2_id = t2.id 
                where m.id = '$match_id'";
    $res = mysqli_query($con, $sql);
    $m = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Result</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 450px; margin: 50px auto;">
        <legend>Update Match Result</legend>
        <p align="center"><?php echo $m['t1n']; ?> <strong>VS</strong> <?php echo $m['t2n']; ?></p>
        <form method="post" action="../controller/matchController.php">
            <input type="hidden" name="match_id" value="<?php echo $m['id']; ?>">
            <input type="hidden" name="t_id" value="<?php echo $m['tournament_id']; ?>">
            Select Winner:
            <select name="winner_id" style="width: 100%;">
                <option value="<?php echo $m['team1_id']; ?>"><?php echo $m['t1n']; ?></option>
                <option value="<?php echo $m['team2_id']; ?>"><?php echo $m['t2n']; ?></option>
                <option value="0">Draw/No Result</option>
            </select><br><br>
            Status:
            <select name="status" style="width: 100%;">
                <option value="In Progress">In Progress</option>
                <option value="Finished">Finished</option>
            </select><br><br>
            <input type="submit" name="update_result" value="Update Result">
        </form>
    </fieldset>
</body>

</html>