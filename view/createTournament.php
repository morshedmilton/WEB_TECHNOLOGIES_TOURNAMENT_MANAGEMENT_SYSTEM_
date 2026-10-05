<?php
require_once '../model/tournamentModel.php';
require_role(['Admin', 'Organizer']);
$mode = 'create';
$t = [];
$active = 'tournaments';
$pageTitle = 'New tournament';
include 'partials/header.php';
?>
<main class="container">
    <div class="crumbs"><a href="tournamentList.php">Tournaments</a> / New</div>
    <div class="page-head"><div><h1>Host a tournament</h1><p>Set the basics now, add teams and fixtures after you publish.</p></div></div>
    <div class="card" style="max-width:820px">
        <?php include 'partials/tournamentForm.php'; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
