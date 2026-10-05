<?php
require_once __DIR__ . '/helpers.php';

/**
 * Create a team and its member rows. $usernames are already validated players.
 * The creator is always a member.
 */
function createTeam($name, $sport, $creatorId, array $memberIds)
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $teamId = db_insert('INSERT INTO teams (name, sport, created_by, created_at) VALUES (?, ?, ?, ?)', [$name, $sport, $creatorId, now()]);
        $memberIds = array_unique(array_merge([$creatorId], $memberIds));
        foreach ($memberIds as $uid) {
            db_exec('INSERT INTO team_members (team_id, user_id) VALUES (?, ?)', [$teamId, $uid]);
        }
        $pdo->commit();
        return $teamId;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function teamNameExists($name)
{
    return (int) db_val('SELECT COUNT(*) FROM teams WHERE LOWER(name) = LOWER(?)', [$name]) > 0;
}

function teamSelect()
{
    return 'SELECT tm.*, u.name AS creator_name, u.username AS creator_username,
                   (SELECT COUNT(*) FROM team_members m WHERE m.team_id = tm.id) AS member_count
            FROM teams tm LEFT JOIN users u ON u.id = tm.created_by';
}

function getAllTeams($sport = '')
{
    if ($sport !== '' && in_array($sport, CATEGORIES, true)) {
        return db_all(teamSelect() . ' WHERE tm.sport = ? ORDER BY tm.id DESC', [$sport]);
    }
    return db_all(teamSelect() . ' ORDER BY tm.id DESC');
}

function getTeamById($id)
{
    return db_one(teamSelect() . ' WHERE tm.id = ?', [$id]);
}

function getTeamMembers($teamId)
{
    return db_all(
        'SELECT u.id, u.name, u.username, u.profile_picture FROM team_members m JOIN users u ON u.id = m.user_id WHERE m.team_id = ? ORDER BY u.name',
        [$teamId]
    );
}

/** Members of many teams in one query, grouped by team id. */
function getMembersForTeams(array $teamIds)
{
    if (!$teamIds) {
        return [];
    }
    $in = implode(',', array_fill(0, count($teamIds), '?'));
    $rows = db_all(
        "SELECT m.team_id, u.id, u.name, u.username, u.profile_picture FROM team_members m JOIN users u ON u.id = m.user_id WHERE m.team_id IN ($in) ORDER BY u.name",
        array_values($teamIds)
    );
    $out = [];
    foreach ($rows as $r) {
        $out[$r['team_id']][] = $r;
    }
    return $out;
}

/** Teams the user created or belongs to. */
function getMyTeams($userId)
{
    return db_all(
        teamSelect() . ' WHERE tm.created_by = ? OR tm.id IN (SELECT team_id FROM team_members WHERE user_id = ?) ORDER BY tm.id DESC',
        [$userId, $userId]
    );
}

function getTeamsByCreator($userId)
{
    return db_all(teamSelect() . ' WHERE tm.created_by = ? ORDER BY tm.name', [$userId]);
}

/** Resolve usernames to player ids. Returns [ids, unknownUsernames]. */
function resolvePlayers(array $usernames)
{
    $ids = [];
    $unknown = [];
    foreach ($usernames as $username) {
        $user = db_one("SELECT id FROM users WHERE username = ? AND role = 'Player' AND status = 'Active'", [$username]);
        if ($user) {
            $ids[] = (int) $user['id'];
        } else {
            $unknown[] = $username;
        }
    }
    return [$ids, $unknown];
}

/* ---------- Registrations ---------- */

function isTeamRegistered($tournamentId, $teamId)
{
    return (int) db_val('SELECT COUNT(*) FROM tournament_registrations WHERE tournament_id = ? AND team_id = ?', [$tournamentId, $teamId]) > 0;
}

function joinTournament($tournamentId, $teamId)
{
    return db_insert(
        'INSERT INTO tournament_registrations (tournament_id, team_id, registered_at) VALUES (?, ?, ?)',
        [$tournamentId, $teamId, now()]
    );
}

function leaveTournament($tournamentId, $teamId)
{
    return db_exec('DELETE FROM tournament_registrations WHERE tournament_id = ? AND team_id = ?', [$tournamentId, $teamId]);
}

function getRegisteredTeams($tournamentId)
{
    return db_all(
        'SELECT tm.*, (SELECT COUNT(*) FROM team_members m WHERE m.team_id = tm.id) AS member_count
         FROM teams tm JOIN tournament_registrations r ON r.team_id = tm.id
         WHERE r.tournament_id = ? ORDER BY r.id',
        [$tournamentId]
    );
}
