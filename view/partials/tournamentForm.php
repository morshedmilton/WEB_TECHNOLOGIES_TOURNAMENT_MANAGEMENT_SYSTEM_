<?php
/** Shared create/edit form. Expects $t (array|null) and $mode ('create'|'edit'). */
$t = isset($t) ? $t : [];
$val = function ($k, $d = '') use ($t) {
    return e(isset($t[$k]) && $t[$k] !== null ? $t[$k] : $d);
};
?>
<form method="post" action="../controller/tournamentController.php" enctype="multipart/form-data" data-validate="tournament" novalidate>
    <?= csrf_field() ?>
    <?php if ($mode === 'edit'): ?><input type="hidden" name="id" value="<?= (int) $t['id'] ?>"><?php endif; ?>
    <div class="field">
        <label for="title">Tournament title</label>
        <input class="input" type="text" name="title" id="title" maxlength="200" value="<?= $val('title') ?>" placeholder="e.g. Dhaka Premier Cricket League 2026" required>
    </div>
    <div class="form-grid">
        <div class="field">
            <label for="category">Sport / category</label>
            <select class="input" name="category" id="category">
                <?php foreach (CATEGORIES as $c): ?><option value="<?= e($c) ?>" <?= ($t['category'] ?? '') === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
            </select>
        </div>
        <?php if ($mode === 'edit'): ?>
            <div class="field">
                <label for="status">Status</label>
                <select class="input" name="status" id="status">
                    <?php foreach (TOURNAMENT_STATUSES as $s): ?><option <?= ($t['status'] ?? '') === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <div class="field">
                <label for="start_date">Start date</label>
                <input class="input" type="date" name="start_date" id="start_date" value="<?= $val('start_date') ?>">
            </div>
        <?php endif; ?>
    </div>
    <div class="form-grid">
        <div class="field">
            <label for="location">Venue / location</label>
            <input class="input" type="text" name="location" id="location" maxlength="150" value="<?= $val('location') ?>" placeholder="Stadium, city or Online">
        </div>
        <div class="field">
            <label for="prize_pool">Prize pool</label>
            <input class="input" type="text" name="prize_pool" id="prize_pool" maxlength="60" value="<?= $val('prize_pool') ?>" placeholder="e.g. BDT 100,000">
        </div>
    </div>
    <div class="form-grid">
        <?php if ($mode === 'edit'): ?>
            <div class="field">
                <label for="start_date">Start date</label>
                <input class="input" type="date" name="start_date" id="start_date" value="<?= $val('start_date') ?>">
            </div>
        <?php endif; ?>
        <div class="field">
            <label for="max_teams">Maximum teams</label>
            <input class="input" type="number" name="max_teams" id="max_teams" min="2" max="64" value="<?= $val('max_teams', 8) ?>">
        </div>
    </div>
    <div class="field">
        <label for="description">Description</label>
        <textarea class="input" name="description" id="description" rows="6" placeholder="Format, rules, eligibility, schedule…" required><?= $val('description') ?></textarea>
    </div>
    <?php if ($mode === 'create'): ?>
        <div class="form-grid">
            <div class="field">
                <label for="banner">Banner image <span class="muted">(optional · JPG/PNG/WebP · 5 MB)</span></label>
                <input class="input" type="file" name="banner" id="banner" accept=".jpg,.jpeg,.png,.webp">
            </div>
            <div class="field">
                <label for="rulebook">Rulebook <span class="muted">(optional · PDF/DOC · 10 MB)</span></label>
                <input class="input" type="file" name="rulebook" id="rulebook" accept=".pdf,.doc,.docx">
            </div>
        </div>
    <?php endif; ?>
    <div class="form-error"></div>
    <div class="row">
        <button class="btn btn-primary btn-lg" type="submit" name="<?= $mode === 'edit' ? 'update' : 'submit' ?>" value="1"><?= $mode === 'edit' ? 'Save changes' : 'Create tournament' ?></button>
        <a class="btn btn-ghost btn-lg" href="<?= $mode === 'edit' ? 'detailsTournament.php?id=' . (int) $t['id'] : 'tournamentList.php' ?>">Cancel</a>
    </div>
</form>
