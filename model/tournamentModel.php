<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/activityModel.php';

/** Base SELECT: tournament + organizer name + registered team count + rating. */
function tournamentSelect()
{
    return 'SELECT t.*, u.name AS organizer_name, u.username AS organizer_username,
                   (SELECT COUNT(*) FROM tournament_registrations r WHERE r.tournament_id = t.id) AS team_count,
                   (SELECT AVG(c.rating) FROM comments c WHERE c.tournament_id = t.id) AS avg_rating,
                   (SELECT COUNT(*) FROM comments c WHERE c.tournament_id = t.id) AS review_count
            FROM tournaments t LEFT JOIN users u ON u.id = t.created_by';
}

function createTournament($t)
{
    return db_insert(
        'INSERT INTO tournaments (title, category, description, banner_image, status, location, start_date, prize_pool, max_teams, created_by, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [$t['title'], $t['category'], $t['description'], $t['banner_image'], 'Upcoming', $t['location'], $t['start_date'] ?: null,
         $t['prize_pool'], (int) $t['max_teams'], $t['created_by'], now()]
    );
}

function updateTournament($id, $t)
{
    return db_exec(
        'UPDATE tournaments SET title = ?, category = ?, description = ?, status = ?, location = ?, start_date = ?, prize_pool = ?, max_teams = ? WHERE id = ?',
        [$t['title'], $t['category'], $t['description'], $t['status'], $t['location'], $t['start_date'] ?: null, $t['prize_pool'], (int) $t['max_teams'], $id]
    );
}

function getTournamentById($id)
{
    return db_one(tournamentSelect() . ' WHERE t.id = ?', [$id]);
}

/** Filterable list. $filters: q, category, status. */
function searchTournaments($filters = [], $limit = 100)
{
    $where = [];
    $params = [];
    if (!empty($filters['q'])) {
        $where[] = '(t.title LIKE ? OR t.category LIKE ? OR t.location LIKE ?)';
        $like = '%' . $filters['q'] . '%';
        array_push($params, $like, $like, $like);
    }
    if (!empty($filters['category']) && in_array($filters['category'], CATEGORIES, true)) {
        $where[] = 't.category = ?';
        $params[] = $filters['category'];
    }
    if (!empty($filters['status']) && in_array($filters['status'], TOURNAMENT_STATUSES, true)) {
        $where[] = 't.status = ?';
        $params[] = $filters['status'];
    }
    $sql = tournamentSelect();
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= " ORDER BY CASE t.status WHEN 'Ongoing' THEN 0 WHEN 'Upcoming' THEN 1 ELSE 2 END, t.start_date DESC, t.id DESC LIMIT " . (int) $limit;
    return db_all($sql, $params);
}

function getFeaturedTournaments($limit = 3)
{
    return db_all(tournamentSelect() . " WHERE t.status <> 'Completed' ORDER BY CASE t.status WHEN 'Ongoing' THEN 0 ELSE 1 END, t.id DESC LIMIT " . (int) $limit);
}

function getTournamentsByTeamIDs(array $teamIds)
{
    if (!$teamIds) {
        return [];
    }
    $in = implode(',', array_fill(0, count($teamIds), '?'));
    return db_all(
        tournamentSelect() . " WHERE t.id IN (SELECT tournament_id FROM tournament_registrations WHERE team_id IN ($in)) ORDER BY t.id DESC",
        array_values($teamIds)
    );
}

/** Deleting cascades manually so it works on every MySQL flavour (no FK dependence). */
function deleteTournament($id)
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        db_exec('DELETE FROM matches WHERE tournament_id = ?', [$id]);
        db_exec('DELETE FROM tournament_registrations WHERE tournament_id = ?', [$id]);
        db_exec('DELETE FROM comments WHERE tournament_id = ?', [$id]);
        db_exec('DELETE FROM attachments WHERE tournament_id = ?', [$id]);
        $n = db_exec('DELETE FROM tournaments WHERE id = ?', [$id]);
        $pdo->commit();
        return $n > 0;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function addAttachment($tournamentId, $name, $path, $type)
{
    return db_insert(
        'INSERT INTO attachments (tournament_id, file_name, file_path, file_type, uploaded_at) VALUES (?, ?, ?, ?, ?)',
        [$tournamentId, $name, $path, $type, now()]
    );
}

function getAttachmentsByTournament($tournamentId)
{
    return db_all('SELECT * FROM attachments WHERE tournament_id = ? ORDER BY id', [$tournamentId]);
}

/* ---------- Dashboard / report aggregates ---------- */

function getActiveTournamentCount()
{
    return (int) db_val("SELECT COUNT(*) FROM tournaments WHERE status <> 'Completed'");
}

function getSiteTotals()
{
    return [
        'tournaments' => (int) db_val('SELECT COUNT(*) FROM tournaments'),
        'active' => getActiveTournamentCount(),
        'teams' => (int) db_val('SELECT COUNT(*) FROM teams'),
        'players' => (int) db_val("SELECT COUNT(*) FROM users WHERE role = 'Player'"),
        'matches' => (int) db_val('SELECT COUNT(*) FROM matches'),
        'finished' => (int) db_val("SELECT COUNT(*) FROM matches WHERE status = 'Finished'"),
    ];
}

function countTournamentsByCategory()
{
    return db_all('SELECT category AS label, COUNT(*) AS value FROM tournaments GROUP BY category ORDER BY value DESC');
}

function countTournamentsByStatus()
{
    $rows = db_all('SELECT status, COUNT(*) AS total FROM tournaments GROUP BY status');
    $out = ['Upcoming' => 0, 'Ongoing' => 0, 'Completed' => 0];
    foreach ($rows as $r) {
        $out[$r['status']] = (int) $r['total'];
    }
    return $out;
}

function getTopRatedTournaments($limit = 5)
{
    return db_all(
        'SELECT t.id, t.title, t.category, AVG(c.rating) AS avg_rating, COUNT(c.id) AS reviews
         FROM tournaments t JOIN comments c ON c.tournament_id = t.id
         GROUP BY t.id, t.title, t.category ORDER BY avg_rating DESC, reviews DESC LIMIT ' . (int) $limit
    );
}
