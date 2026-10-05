<?php
/** Reusable view fragments. */

function status_badge($status)
{
    return '<span class="badge badge-' . badge_class($status) . '">' . e($status) . '</span>';
}

function crest($name, $size = 34)
{
    $hue = abs(crc32($name)) % 360;
    $letters = strtoupper(preg_replace('/[^A-Za-z]/', '', implode('', array_map(function ($w) {
        return substr($w, 0, 1);
    }, array_slice(preg_split('/\s+/', trim($name)), 0, 2)))));
    return '<span class="crest" style="width:' . (int) $size . 'px;height:' . (int) $size . 'px;background:linear-gradient(135deg,hsl(' . $hue . ' 70% 52%),hsl(' . (($hue + 40) % 360) . ' 70% 38%))">' . e($letters ?: '?') . '</span>';
}

function tournament_banner($t, $large = false)
{
    $meta = category_meta($t['category']);
    $style = banner_style($t['category']);
    $cover = '';
    if (!empty($t['banner_image']) && is_file(__DIR__ . '/../../uploads/banners/' . $t['banner_image'])) {
        $cover = '<img class="cover" src="../uploads/banners/' . e($t['banner_image']) . '" alt="">';
    }
    $html = '<div class="banner' . ($large ? ' banner-lg' : '') . '" style="' . $style . '">' . $cover;
    if (!$cover) {
        $html .= '<span class="sport">' . icon($meta['icon'], 120) . '</span>';
    }
    $html .= '<div class="corner">' . status_badge_glass($t['status']) . '<span class="badge badge-glass">' . e($t['category']) . '</span></div></div>';
    return $html;
}

function status_badge_glass($status)
{
    if ($status === 'Ongoing') {
        return '<span class="badge badge-live" style="background:#fff">Live</span>';
    }
    return '<span class="badge badge-glass">' . e($status) . '</span>';
}

function tournament_card($t, $href = 'detailsTournament.php')
{
    $max = max(1, (int) $t['max_teams']);
    $pct = min(100, round(((int) $t['team_count'] / $max) * 100));
    ob_start(); ?>
    <a class="card t-card" href="<?= e($href) ?>?id=<?= (int) $t['id'] ?>">
        <?= tournament_banner($t) ?>
        <div class="body">
            <h3><?= e($t['title']) ?></h3>
            <div class="meta">
                <?php if (!empty($t['location'])): ?><span><?= icon('map-pin', 14) ?> <?= e($t['location']) ?></span><?php endif; ?>
                <?php if (!empty($t['start_date'])): ?><span><?= icon('calendar', 14) ?> <?= e(fmt_date($t['start_date'])) ?></span><?php endif; ?>
            </div>
            <div>
                <div class="row row-between small muted" style="margin-bottom:6px">
                    <span><?= (int) $t['team_count'] ?>/<?= $max ?> teams</span>
                    <?php if (!empty($t['prize_pool'])): ?><span class="prize"><?= icon('trophy', 14) ?> <?= e($t['prize_pool']) ?></span><?php endif; ?>
                </div>
                <div class="progress"><i style="width:<?= $pct ?>%"></i></div>
            </div>
            <div class="foot">
                <span class="muted">by <?= e($t['organizer_name'] ?: 'Unknown') ?></span>
                <?php if ($t['review_count'] > 0): ?>
                    <span><?= stars_html($t['avg_rating']) ?> <span class="muted small"><?= number_format((float) $t['avg_rating'], 1) ?></span></span>
                <?php else: ?>
                    <span class="muted small">No reviews yet</span>
                <?php endif; ?>
            </div>
        </div>
    </a>
    <?php
    return ob_get_clean();
}

function match_row($m, $teamIds = [])
{
    $finished = $m['status'] === 'Finished';
    $w1 = $finished && (int) $m['winner_id'] === (int) $m['team1_id'];
    $w2 = $finished && (int) $m['winner_id'] === (int) $m['team2_id'];
    $mine1 = in_array($m['team1_id'], $teamIds);
    $mine2 = in_array($m['team2_id'], $teamIds);
    ob_start(); ?>
    <div class="match">
        <div class="team <?= $w1 ? 'win' : '' ?>"><?= crest($m['team1_name']) ?><span><?= e($m['team1_name']) ?><?= $mine1 ? ' (you)' : '' ?></span></div>
        <div class="center-col">
            <?php if ($m['status'] === 'Scheduled'): ?>
                <div class="score muted" style="font-size:1rem">VS</div>
            <?php else: ?>
                <div class="score"><?= (int) $m['team1_score'] ?> – <?= (int) $m['team2_score'] ?></div>
            <?php endif; ?>
            <div class="when"><?= e(fmt_date($m['match_date'], 'M j · g:i A')) ?></div>
            <div style="margin-top:4px"><?= status_badge($m['status']) ?></div>
        </div>
        <div class="team right <?= $w2 ? 'win' : '' ?>"><span><?= e($m['team2_name']) ?><?= $mine2 ? ' (you)' : '' ?></span><?= crest($m['team2_name']) ?></div>
    </div>
    <?php
    return ob_get_clean();
}

function empty_state($icon, $title, $text = '', $actionHtml = '')
{
    return '<div class="empty"><div class="big">' . icon($icon, 44) . '</div><h3>' . e($title) . '</h3><p>' . e($text) . '</p>' . $actionHtml . '</div>';
}

function stat_tile($icon, $tone, $value, $label)
{
    return '<div class="card stat"><div class="stat-icon tone-' . $tone . '">' . icon($icon, 24) . '</div><div><div class="stat-value">' . e($value) . '</div><div class="stat-label">' . e($label) . '</div></div></div>';
}
