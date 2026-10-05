<?php
require_once '../model/activityModel.php';
$user = require_role('Admin');
$logs = getRecentActivities(100);
$active = 'activity';
$pageTitle = 'Activity log';
include 'partials/header.php';
?>
<main class="container">
    <div class="page-head"><div><h1>Activity log</h1><p>Audit trail of the 100 most recent actions on the platform.</p></div></div>
    <div class="card">
        <?php if ($logs): ?>
            <ul class="timeline">
                <?php foreach ($logs as $log): ?>
                    <li>
                        <div class="row row-between wrap">
                            <span><?= e($log['activity_text']) ?></span>
                            <span class="muted small"><?php if ($log['user_name']): ?>@<?= e($log['username']) ?> · <?php endif; ?><?= e(fmt_date($log['created_at'], 'M j, g:i A')) ?> (<?= e(time_ago($log['created_at'])) ?>)</span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?><?= empty_state('file-text', 'No activity recorded yet') ?><?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
