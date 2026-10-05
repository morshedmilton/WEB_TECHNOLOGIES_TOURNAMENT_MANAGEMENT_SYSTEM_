<?php
require_once '../model/helpers.php';
$pageTitle = 'Help & FAQ';
include 'partials/header.php';
$faqs = [
    ['How do I create a tournament?', 'Sign in with an Organizer or Admin account, open Tournaments and click “New tournament”. Add the sport, venue, dates and a description, then publish. You can attach a banner and rulebook too.'],
    ['How does a team join a tournament?', 'Create a team for the right sport, then open the tournament page and use “Register your team”. Only the team captain can register or withdraw, and a team can enter any tournament of its own sport while slots remain.'],
    ['How are standings calculated?', 'The league table is computed live from finished matches: 3 points for a win, 1 for a draw, 0 for a loss. Ties are broken by score difference, then team name.'],
    ['Who can update match results?', 'The tournament organiser and platform admins. Open the Matches tab, choose “Update result”, enter the scores and mark the match Finished.'],
    ['Can I participate in multiple tournaments?', 'Yes. Use the same team, or create more teams. One team can be registered in many tournaments at once.'],
    ['I forgot my password.', 'Use “Forgot password” on the sign-in page. If you are signed in, you can change your password from your profile.'],
    ['Where is my data stored?', 'In a managed cloud MySQL database over an encrypted TLS connection. Passwords are stored as salted bcrypt hashes, never in plain text.'],
];
?>
<main class="container" style="max-width:820px">
    <div class="page-head"><div><h1>Help &amp; FAQ</h1><p>Quick answers to the most common questions.</p></div></div>
    <div class="faq">
        <?php foreach ($faqs as $i => $f): ?>
            <details <?= $i === 0 ? 'open' : '' ?>><summary><?= e($f[0]) ?></summary><p><?= e($f[1]) ?></p></details>
        <?php endforeach; ?>
    </div>
    <p class="center muted mt-3">Still stuck? <a href="contact.php">Send us a message</a>.</p>
</main>
<?php include 'partials/footer.php'; ?>
