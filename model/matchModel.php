<?php
require_once __DIR__ . '/helpers.php';

function matchSelect()
{
    return 'SELECT m.*, t1.name AS team1_name, t2.name AS team2_name, tw.name AS winner_name, tour.title AS tournament_title, tour.category AS tournament_category
            FROM matches m
            JOIN teams t1 ON m.team1_id = t1.id
            JOIN teams t2 ON m.team2_id = t2.id
            JOIN tournaments tour ON m.tournament_id = tour.id
            LEFT JOIN teams tw ON m.winner_id = tw.id';
}

function scheduleMatch($tournamentId, $team1, $team2, $date)
{
    return db_insert(
        "INSERT INTO matches (tournament_id, team1_id, team2_id, match_date, status) VALUES (?, ?, ?, ?, 'Scheduled')",
        [$tournamentId, $team1, $team2, $date]
    );
}

function getMatchById($id)
{
    return db_one(matchSelect() . ' WHERE m.id = ?', [$id]);
}

function getMatchesByTournament($tournamentId)
{
    return db_all(matchSelect() . ' WHERE m.tournament_id = ? ORDER BY m.match_date ASC', [$tournamentId]);
}

function updateMatchResult($matchId, $score1, $score2, $status)
{
    $match = getMatchById($matchId);
    $winner = null;
    if ($status === 'Finished' && $score1 !== $score2) {
        $winner = $score1 > $score2 ? $match['team1_id'] : $match['team2_id'];
    }
    return db_exec(
        'UPDATE matches SET team1_score = ?, team2_score = ?, winner_id = ?, status = ? WHERE id = ?',
        [$score1, $score2, $winner, $status, $matchId]
    );
}

function deleteMatch($id)
{
    return db_exec('DELETE FROM matches WHERE id = ?', [$id]);
}

function getMatchesByTeamIDs(array $teamIds)
{
    if (!$teamIds) {
        return [];
    }
    $in = implode(',', array_fill(0, count($teamIds), '?'));
    $ids = array_values($teamIds);
    return db_all(
        matchSelect() . " WHERE m.team1_id IN ($in) OR m.team2_id IN ($in) ORDER BY m.match_date DESC",
        array_merge($ids, $ids)
    );
}

function getUpcomingMatches($limit = 5)
{
    return db_all(matchSelect() . " WHERE m.status <> 'Finished' ORDER BY m.match_date ASC LIMIT " . (int) $limit);
}

function getRecentResults($limit = 5)
{
    return db_all(matchSelect() . " WHERE m.status = 'Finished' ORDER BY m.match_date DESC LIMIT " . (int) $limit);
}

/**
 * League table computed from finished matches: 3 pts win, 1 pt draw.
 * $teams is the list of registered teams so teams with no games still appear.
 */
function computeStandings(array $teams, array $matches)
{
    $table = [];
    foreach ($teams as $team) {
        $table[$team['id']] = ['id' => $team['id'], 'name' => $team['name'], 'p' => 0, 'w' => 0, 'd' => 0, 'l' => 0, 'for' => 0, 'against' => 0, 'pts' => 0];
    }
    foreach ($matches as $m) {
        if ($m['status'] !== 'Finished' || !isset($table[$m['team1_id']]) || !isset($table[$m['team2_id']])) {
            continue;
        }
        $a = &$table[$m['team1_id']];
        $b = &$table[$m['team2_id']];
        $a['p']++;
        $b['p']++;
        $a['for'] += (int) $m['team1_score'];
        $a['against'] += (int) $m['team2_score'];
        $b['for'] += (int) $m['team2_score'];
        $b['against'] += (int) $m['team1_score'];
        if ($m['team1_score'] > $m['team2_score']) {
            $a['w']++; $a['pts'] += 3; $b['l']++;
        } elseif ($m['team1_score'] < $m['team2_score']) {
            $b['w']++; $b['pts'] += 3; $a['l']++;
        } else {
            $a['d']++; $b['d']++; $a['pts']++; $b['pts']++;
        }
        unset($a, $b);
    }
    $rows = array_values($table);
    usort($rows, function ($x, $y) {
        if ($x['pts'] !== $y['pts']) {
            return $y['pts'] - $x['pts'];
        }
        $dx = $x['for'] - $x['against'];
        $dy = $y['for'] - $y['against'];
        if ($dx !== $dy) {
            return $dy - $dx;
        }
        return strcmp($x['name'], $y['name']);
    });
    return $rows;
}
