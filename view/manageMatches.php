<?php
session_start();
require_once('../model/teamModel.php');
require_once('../model/tournamentModel.php');
if (!isset($_COOKIE['status'])) {
    header('location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $t_id = $_GET['id'];
    $tournament = getTournamentById($t_id);
    $teams = getRegisteredTeams($t_id);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Schedule Match</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 500px; margin: 30px auto;">
        <legend>Schedule New Match</legend>
        <div style="text-align: center;"><a href="detailsTournament.php?id=<?php echo $t_id; ?>">Back to Tournament</a>
        </div>

        <div style="text-align: center; margin-top: 15px;">
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'same_team')
                    echo "<span style='color: red;'>Error: Team 1 and Team 2 cannot be the same!</span>";
                if ($_GET['error'] == 'db_error')
                    echo "<span style='color: red;'>Database error! Please try again.</span>";
            }
            ?>
        </div>

        <form method="post" action="../controller/matchController.php">
            <input type="hidden" name="tournament_id" value="<?php echo $t_id; ?>">
            <p>Tournament: <strong><?php echo $tournament['title']; ?></strong></p>

            Team 1:
            <select name="team1_id" style="width: 95%;">
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo $team['id']; ?>"><?php echo $team['name']; ?></option>
                <?php endforeach; ?>
            </select><br>

            Team 2:
            <select name="team2_id" style="width: 95%;">
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo $team['id']; ?>"><?php echo $team['name']; ?></option>
                <?php endforeach; ?>
            </select><br>

            Match Date:
            <input type="datetime-local" name="match_date" style="width: 95%;" required><br><br>

            <input type="submit" name="schedule" value="Confirm Schedule">
        </form>
    </fieldset>
</body>

</html>