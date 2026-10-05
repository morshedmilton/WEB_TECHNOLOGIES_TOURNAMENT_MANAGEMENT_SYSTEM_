    <?php if (empty($authLayout)): ?>
    <footer class="footer">
        <div class="container">
            <span>© <?= date('Y') ?> TourneyHub · PHP · MySQL · Vanilla JS</span>
            <span><a href="faq.php">FAQ</a><a href="contact.php">Contact</a></span>
        </div>
    </footer>
    <?php endif; ?>

    <?php $toasts = flash_pull(); ?>
    <?php if ($toasts): ?>
        <div class="toasts" role="status">
            <?php foreach ($toasts as $t): ?>
                <div class="toast <?= e($t['type']) ?>">
                    <span class="toast-icon"><?= icon($t['type'] === 'success' ? 'check-circle' : ($t['type'] === 'error' ? 'alert' : 'info'), 20) ?></span>
                    <span><?= e($t['message']) ?></span>
                    <button class="x" type="button" aria-label="Dismiss">×</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <dialog id="confirmDialog">
        <h3>Are you sure?</h3>
        <p class="muted mb-0" data-msg></p>
        <div class="row">
            <button class="btn btn-ghost" type="button" data-cancel>Cancel</button>
            <button class="btn btn-danger" type="button" data-ok>Yes, continue</button>
        </div>
    </dialog>

    <script src="../asset/js/app.js"></script>
</body>

</html>
