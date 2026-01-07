<?php
session_start();
require_once('../model/teamModel.php');
require_once('../model/tournamentModel.php');
require_once('../model/matchModel.php');

if (!isset($_COOKIE['status'])) {
    header('location: login.php');
    exit();
}

$username = $_SESSION['username'];

// 1. Find all teams of the user
$myTeams = getMyTeams($username);
$teamIds = [];
foreach ($myTeams as $team) {
    $teamIds[] = $team['id'];
}

$myTournaments = [];
$myMatches = [];

// 2. If teams exist, then search for tournaments and matches
if (!empty($teamIds)) {
    $idsString = implode(',', $teamIds);
    $myTournaments = getTournamentsByTeamIDs($idsString);
    $myMatches = getMatchesByTeamIDs($idsString);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Matches & Tournaments</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 850px; margin: 30px auto;">
        <legend>My Activity Log</legend>
        <div style="text-align: center;">
            <a href="home.php">Dashboard</a> |
            <a href="tournamentList.php">All Tournaments</a>
        </div>
        <hr>

        <h3>My Registered Tournaments</h3>
        <?php if (count($myTournaments) > 0): ?>
            <table border="1" cellspacing="0" cellpadding="8" style="width: 100%; text-align: center;">
                <tr style="background-color: #eee;">
                    <th>ID</th>
                    <th>Tournament Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($myTournaments as $t): ?>
                    <tr>
                        <td>
                            <?php echo $t['id']; ?>
                        </td>
                        <td>
                            <?php echo $t['title']; ?>
                        </td>
                        <td>
                            <?php echo $t['category']; ?>
                        </td>
                        <td>
                            <?php
                            if ($t['status'] == 'Upcoming')
                                echo '<span style="color: blue;">Upcoming</span>';
                            elseif ($t['status'] == 'Ongoing')
                                echo '<span style="color: green;">Ongoing</span>';
                            else
                                echo '<span style="color: red;">Completed</span>';
                            ?>
                        </td>
                        <td><a href="detailsTournament.php?id=<?php echo $t['id']; ?>">View Details</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: gray;">You haven't joined any tournaments yet.</p>
        <?php endif; ?>

        <hr>

        <h3>My Match Schedule</h3>
        <?php if (count($myMatches) > 0): ?>
            <table border="1" cellspacing="0" cellpadding="8" style="width: 100%; text-align: center;">
                <tr style="background-color: #e0f7fa;">
                    <th>Date</th>
                    <th>Tournament</th>
                    <th>Match</th>
                    <th>Status</th>
                    <th>Result/Winner</th>
                </tr>
                <?php foreach ($myMatches as $m): ?>
                    <tr>
                        <td>
                            <?php echo date('M d, h:i A', strtotime($m['match_date'])); ?>
                        </td>
                        <td>
                            <?php echo $m['tournament_title']; ?>
                        </td>
                        <td>
                            <?php
                            // Highlight own team
                            $t1 = in_array($m['team1_id'], $teamIds) ? "<b>{$m['team1_name']} (You)</b>" : $m['team1_name'];
                            $t2 = in_array($m['team2_id'], $teamIds) ? "<b>{$m['team2_name']} (You)</b>" : $m['team2_name'];
                            echo "$t1 vs $t2";
                            ?>
                        </td>
                        <td>
                            <?php echo $m['status']; ?>
                        </td>
                        <td style="font-weight: bold; color: green;">
                            <?php echo $m['winner_name'] ? $m['winner_name'] : "TBD"; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: gray;">No scheduled matches found for your teams.</p>
        <?php endif; ?>

    </fieldset>
</body>

</html>