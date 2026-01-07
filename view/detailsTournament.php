<?php
session_start();
require_once('../model/tournamentModel.php');
require_once('../model/teamModel.php');
require_once('../model/matchModel.php');
require_once('../model/commentModel.php');

if (!isset($_COOKIE['status'])) {
    header('location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $t_id = $_GET['id'];
    $t = getTournamentById($t_id);

    // [FIX] Fetching team and registration data to fix connection
    $myTeams = getTeamsByCreator($_SESSION['username']);
    $registeredTeams = getRegisteredTeams($t_id);

    $matches = getMatchesByTournament($t_id);
    $comments = getCommentsByTournament($t_id);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tournament Details</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <fieldset style="width: 800px; margin: 30px auto;">
        <legend>Tournament: <?php echo $t['title']; ?></legend>
        <div style="text-align: center;">
            <a href="tournamentList.php">Back to List</a> | <a href="home.php">Dashboard</a>
        </div>
        <hr>

        <?php if (!empty($t['banner_image'])): ?>
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="../uploads/banners/<?php echo $t['banner_image']; ?>"
                    style="width: 100%; height: 200px; object-fit: cover; border: 2px solid #000; border-radius: 8px;">
            </div>
            <hr>
        <?php endif; ?>

        <h3>Tournament Info</h3>
        <p><strong>Category:</strong> <?php echo $t['category']; ?></p>
        <p><strong>Description:</strong> <?php echo $t['description']; ?></p>

        <hr>

        <h3>Registered Teams</h3>
        <?php
        if (count($registeredTeams) > 0) {
            $teamNames = [];
            foreach ($registeredTeams as $rt) {
                $teamNames[] = $rt['name'];
            }
            echo "<p><strong>Teams:</strong> " . implode(", ", $teamNames) . "</p>";
        } else {
            echo "<p>No teams registered yet.</p>";
        }
        ?>

        <?php if ($_SESSION['role'] == 'Player' || $_SESSION['role'] == 'Organizer'): ?>
            <div style="background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                <strong>Join this Tournament:</strong>
                <?php if (count($myTeams) > 0): ?>
                    <form action="../controller/teamController.php" method="POST" style="display: inline-block;">
                        <input type="hidden" name="tournament_id" value="<?php echo $t_id; ?>">
                        <select name="team_id" required style="padding: 5px;">
                            <option value="">Select your team</option>
                            <?php foreach ($myTeams as $team): ?>
                                <option value="<?php echo $team['id']; ?>"><?php echo $team['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" name="join" value="Join Now" style="width: auto; padding: 5px 15px; margin: 0;">
                    </form>
                <?php else: ?>
                    <span style="color: red;">You need to <a href="createTeam.php" style="display:inline;">create a team</a>
                        first to join.</span>
                <?php endif; ?>

                <div style="margin-top:5px;">
                    <?php
                    if (isset($_GET['success']) && $_GET['success'] == 'joined')
                        echo "<span style='color: green;'>Successfully joined!</span>";
                    if (isset($_GET['error']) && $_GET['error'] == 'db_error')
                        echo "<span style='color: red;'>Database error or already joined!</span>";
                    ?>
                </div>
            </div>
            <hr>
        <?php endif; ?>


        <h3>Tournament Documents</h3>
        <?php
        $attachments = getAttachmentsByTournament($t_id);
        if (count($attachments) > 0) {
            foreach ($attachments as $file) {
                echo "<a href='../uploads/docs/{$file['file_path']}' target='_blank' style='text-align: left; display: block;'>📄 {$file['file_name']}</a>";
            }
        } else {
            echo "<p>No documents uploaded.</p>";
        }
        ?>
        <hr>

        <h3>Match Schedule & Results</h3>

        <?php if ($_SESSION['role'] == 'Admin' || $_SESSION['username'] == $t['created_by']): ?>
            <div style="text-align: center; margin-bottom: 15px;">
                <a href="manageMatches.php?id=<?php echo $t_id; ?>"
                    style="background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    Generate / Schedule New Match
                </a>

                <div style="margin-top:10px;">
                    <?php
                    if (isset($_GET['success']) && $_GET['success'] == 'scheduled')
                        echo "<span style='color: green;'>Match scheduled successfully!</span>";
                    if (isset($_GET['success']) && $_GET['success'] == 'updated')
                        echo "<span style='color: green;'>Result updated successfully!</span>";
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <table border="1" cellspacing="0" cellpadding="8" style="width: 100%; text-align: center;">
            <tr style="background-color: #eee;">
                <th>Date</th>
                <th>Match</th>
                <th>Status</th>
                <th>Winner</th>
                <th>Action</th>
            </tr>
            <?php foreach ($matches as $m): ?>
                <tr>
                    <td><?php echo date('M d, h:i A', strtotime($m['match_date'])); ?></td>
                    <td><?php echo $m['team1_name']; ?> vs <?php echo $m['team2_name']; ?></td>
                    <td><?php echo $m['status']; ?></td>
                    <td style="font-weight: bold; color: green;">
                        <?php echo $m['winner_name'] ? $m['winner_name'] : "TBD"; ?>
                    </td>
                    <td>
                        <?php if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Organizer'): ?>
                            <a href="updateMatch.php?match_id=<?php echo $m['id']; ?>">Update</a>
                        <?php else: ?>
                            ---
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <hr>
        <h3>Ratings & Comments</h3>
        <div style="text-align: center; margin-top: 10px;">
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'empty_comment')
                    echo "<span style='color: red;'>Please write something before posting!</span>";
                if ($_GET['error'] == 'db_error')
                    echo "<span style='color: red;'>Database error. Try again!</span>";
            }
            if (isset($_GET['success'])) {
                if ($_GET['success'] == 'commented')
                    echo "<span style='color: green;'>Thank you for your feedback!</span>";
            }
            ?>
        </div>
        <form method="post" action="../controller/commentController.php">
            <input type="hidden" name="tournament_id" value="<?php echo $t_id; ?>">
            Rating:
            <select name="rating">
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select><br>
            <textarea name="comment" placeholder="Write your feedback..." style="width: 95%; margin-top: 5px;"
                rows="3"></textarea><br>
            <input type="submit" name="postComment" value="Post Review" style="width: auto; padding: 5px 15px;">
        </form>

        <div style="margin-top: 20px;">
            <?php foreach ($comments as $c): ?>
                <div style="border-bottom: 1px solid #ccc; padding: 10px 0;">
                    <strong><?php echo $c['username']; ?></strong> (Rating: <?php echo $c['rating']; ?>/5) <br>
                    <?php echo $c['comment']; ?> <br>
                    <small style="color: gray;"><?php echo $c['created_at']; ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </fieldset>
</body>

</html>