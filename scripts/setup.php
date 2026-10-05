<?php
/**
 * Database bootstrap: creates the schema and (on an empty database) seeds
 * realistic demo data. Safe to run on every deploy - it is idempotent.
 *
 *   php scripts/setup.php           create tables, seed if empty
 *   php scripts/setup.php --reset   drop everything and start over
 */
require_once __DIR__ . '/../model/helpers.php';

function schema_statements($mysql)
{
    $id = $mysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $tail = $mysql ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4' : '';

    return [
        "CREATE TABLE IF NOT EXISTS users (
            id $id,
            name VARCHAR(100) NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL DEFAULT 'Player',
            status VARCHAR(20) NOT NULL DEFAULT 'Active',
            profile_picture VARCHAR(255) DEFAULT NULL,
            created_at DATETIME NOT NULL
        )$tail",
        "CREATE TABLE IF NOT EXISTS tournaments (
            id $id,
            title VARCHAR(200) NOT NULL,
            category VARCHAR(50) NOT NULL,
            description TEXT,
            banner_image VARCHAR(255) DEFAULT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'Upcoming',
            location VARCHAR(150) DEFAULT NULL,
            start_date DATE DEFAULT NULL,
            prize_pool VARCHAR(60) DEFAULT NULL,
            max_teams INT NOT NULL DEFAULT 8,
            created_by INT DEFAULT NULL,
            created_at DATETIME NOT NULL
        )$tail",
        "CREATE TABLE IF NOT EXISTS teams (
            id $id,
            name VARCHAR(100) NOT NULL UNIQUE,
            sport VARCHAR(50) NOT NULL,
            created_by INT DEFAULT NULL,
            created_at DATETIME NOT NULL
        )$tail",
        "CREATE TABLE IF NOT EXISTS team_members (
            team_id INT NOT NULL,
            user_id INT NOT NULL,
            PRIMARY KEY (team_id, user_id)
        )$tail",
        "CREATE TABLE IF NOT EXISTS tournament_registrations (
            id $id,
            tournament_id INT NOT NULL,
            team_id INT NOT NULL,
            registered_at DATETIME NOT NULL,
            UNIQUE (tournament_id, team_id)
        )$tail",
        "CREATE TABLE IF NOT EXISTS matches (
            id $id,
            tournament_id INT NOT NULL,
            team1_id INT NOT NULL,
            team2_id INT NOT NULL,
            match_date DATETIME NOT NULL,
            team1_score INT DEFAULT NULL,
            team2_score INT DEFAULT NULL,
            winner_id INT DEFAULT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'Scheduled'
        )$tail",
        "CREATE TABLE IF NOT EXISTS comments (
            id $id,
            tournament_id INT NOT NULL,
            user_id INT NOT NULL,
            comment TEXT NOT NULL,
            rating INT NOT NULL DEFAULT 5,
            created_at DATETIME NOT NULL
        )$tail",
        "CREATE TABLE IF NOT EXISTS attachments (
            id $id,
            tournament_id INT NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_type VARCHAR(50) DEFAULT NULL,
            uploaded_at DATETIME NOT NULL
        )$tail",
        "CREATE TABLE IF NOT EXISTS activity_log (
            id $id,
            user_id INT DEFAULT NULL,
            activity_text VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL
        )$tail",
        "CREATE TABLE IF NOT EXISTS contact_messages (
            id $id,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(150) NOT NULL,
            message TEXT NOT NULL,
            created_at DATETIME NOT NULL
        )$tail",
    ];
}

function index_statements()
{
    return [
        'CREATE INDEX idx_matches_tournament ON matches (tournament_id)',
        'CREATE INDEX idx_comments_tournament ON comments (tournament_id)',
        'CREATE INDEX idx_activity_created ON activity_log (created_at)',
        'CREATE INDEX idx_tournaments_status ON tournaments (status)',
    ];
}

function setup_reset()
{
    foreach (['contact_messages', 'activity_log', 'attachments', 'comments', 'matches', 'tournament_registrations', 'team_members', 'teams', 'tournaments', 'users'] as $table) {
        db()->exec("DROP TABLE IF EXISTS $table");
    }
}

function setup_schema()
{
    $mysql = db_driver() !== 'sqlite';
    foreach (schema_statements($mysql) as $sql) {
        db()->exec($sql);
    }
    foreach (index_statements() as $sql) {
        try {
            db()->exec($sql);
        } catch (PDOException $e) {
            // index already exists - fine on re-runs
        }
    }
}

function setup_seed()
{
    mt_srand(2026);
    $pdo = db();
    $pdo->beginTransaction();

    $adminHash = password_hash('Admin@123', PASSWORD_DEFAULT);
    $orgHash = password_hash('Organizer@123', PASSWORD_DEFAULT);
    $playerHash = password_hash('Player@123', PASSWORD_DEFAULT);
    $joined = date('Y-m-d H:i:s', strtotime('-45 days'));

    $addUser = function ($name, $username, $role, $hash) use ($joined) {
        return db_insert(
            'INSERT INTO users (name, username, email, password_hash, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [$name, $username, $username . '@example.com', $hash, $role, 'Active', $joined]
        );
    };

    $addUser('Admin', 'admin', 'Admin', $adminHash);
    $org1 = $addUser('Nadia Rahman', 'organizer', 'Organizer', $orgHash);
    $org2 = $addUser('Arif Chowdhury', 'arif.chowdhury', 'Organizer', $orgHash);

    $names = [
        'Rafiq Ahmed', 'Tanvir Hossain', 'Sadia Islam', 'Imran Khan', 'Mehedi Hasan', 'Farhana Akter', 'Nafis Karim', 'Shuvo Das',
        'Ayesha Siddiqua', 'Rakib Uddin', 'Tasnim Jahan', 'Zubair Alam', 'Mahin Chowdhury', 'Labiba Noor', 'Sakib Rahman', 'Nusrat Jahan',
        'Fahim Reza', 'Anika Tabassum', 'Arman Sheikh', 'Mim Sultana', 'Raihan Mia', 'Joya Paul', 'Tahmid Ali', 'Sumaiya Binte',
        'Kamrul Hasan', 'Priya Sen', 'Ovi Mondal', 'Rumana Parvin', 'Sabbir Ahmed', 'Tisha Roy', 'Habib Khan', 'Maisha Rahim',
    ];
    $players = [];
    foreach ($names as $i => $name) {
        $username = $i === 0 ? 'player' : strtolower(str_replace(' ', '.', $name));
        $players[] = $addUser($name, $username, 'Player', $playerHash);
    }

    /* ---- teams ---- */
    $teamDefs = [
        'Cricket' => ['Dhaka Dynamos', 'Chattogram Challengers', 'Sylhet Strikers', 'Rajshahi Royals', 'Khulna Titans', 'Barishal Bulls'],
        'Football' => ['Rangpur Rangers FC', 'Comilla United', 'Mymensingh Mariners', 'Gazipur Gladiators', 'Bogura Blazers', 'Coastal Waves FC'],
        'Basketball' => ['Metro Hoopers', 'Skyline Dunkers', 'Riverside Rebels', 'Northside Knights'],
        'Volleyball' => ['Spike Squad', 'Net Ninjas', 'Block Party', 'Sand Sharks'],
        'Badminton' => ['Shuttle Storm', 'Smash Syndicate', 'Feather Force', 'Court Crushers'],
        'E-Sports' => ['Team Phantom', 'Neon Wolves', 'Pixel Pirates', 'Glitch Gang', 'Apex Aces', 'Rogue Reign'],
    ];
    $teams = [];
    $cursor = 0;
    foreach ($teamDefs as $sport => $list) {
        foreach ($list as $teamName) {
            $creator = $players[$cursor % count($players)];
            $teamId = db_insert('INSERT INTO teams (name, sport, created_by, created_at) VALUES (?, ?, ?, ?)', [$teamName, $sport, $creator, date('Y-m-d H:i:s', strtotime('-' . mt_rand(20, 40) . ' days'))]);
            $size = mt_rand(3, 5);
            for ($k = 0; $k < $size; $k++) {
                db_exec('INSERT INTO team_members (team_id, user_id) VALUES (?, ?)', [$teamId, $players[($cursor + $k) % count($players)]]);
            }
            $teams[$sport][] = $teamId;
            $cursor += 2;
        }
    }

    /* ---- tournaments ---- */
    $tournamentDefs = [
        ['Dhaka Premier Cricket League 2026', 'Cricket', 'Ongoing', 'Mirpur Sher-e-Bangla Stadium, Dhaka', -10, 'BDT 500,000', 8, 6, $org1, 1,
            'The flagship T20 league of the season. Six franchises, a full round-robin, and the top of the table lifts the trophy. Matches are played under lights with live scoring and post-match player awards.'],
        ['Inter-University Football Cup', 'Football', 'Upcoming', 'BUET Central Field, Dhaka', 14, 'BDT 250,000', 8, 5, $org2, 0,
            'Eight universities, one cup. Group stage followed by knockouts, officiated by licensed referees. Squads of up to 16; registration closes one week before kickoff.'],
        ['Valorant Campus Showdown', 'E-Sports', 'Ongoing', 'Online (Discord + Custom Lobbies)', -8, 'BDT 150,000', 8, 6, $org1, 2,
            'A best-of-three round robin for campus Valorant squads. Matches are streamed with caster commentary every evening. Anti-cheat client mandatory.'],
        ['City Badminton Open', 'Badminton', 'Completed', 'Bangabandhu National Stadium Hall, Dhaka', -40, 'BDT 80,000', 4, 4, $org2, 3,
            'Doubles tournament across four clubs. Best-of-three games, 21-point rally scoring. A fast, high-energy weekend that ended with a thrilling final.'],
        ['Winter Basketball Classic', 'Basketball', 'Upcoming', 'Dhanmondi Indoor Arena, Dhaka', 30, 'BDT 120,000', 8, 3, $org1, 4,
            'Five-on-five league play with a seeded playoff. Four quarters of ten minutes, full NBA-style shot clock. Spectators welcome.'],
        ['Spring Volleyball Invitational', 'Volleyball', 'Completed', 'Chattogram Sports Complex', -60, 'BDT 60,000', 4, 4, $org2, 5,
            'An invitational for four top club sides, best-of-five sets with rally scoring. Concluded with record attendance.'],
        ['Chattogram Champions Trophy', 'Football', 'Ongoing', 'MA Aziz Stadium, Chattogram', -5, 'BDT 300,000', 8, 6, $org1, 1,
            'Six-team round robin held at the historic MA Aziz Stadium. Two fixtures a day, with the top side crowned champion at the final whistle.'],
    ];
    $tournamentIds = [];
    foreach ($tournamentDefs as $def) {
        list($title, $cat, $status, $location, $offset, $prize, $max, $count, $creator, $teamSlice, $desc) = $def;
        $start = date('Y-m-d', strtotime(($offset >= 0 ? '+' : '') . $offset . ' days'));
        $tid = db_insert(
            'INSERT INTO tournaments (title, category, description, banner_image, status, location, start_date, prize_pool, max_teams, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$title, $cat, $desc, null, $status, $location, $start, $prize, $max, $creator, date('Y-m-d H:i:s', strtotime('-' . (abs($offset) + 12) . ' days'))]
        );
        $tournamentIds[] = $tid;
        $regTeams = array_slice($teams[$cat], 0, $count);
        foreach ($regTeams as $k => $teamId) {
            db_exec('INSERT INTO tournament_registrations (tournament_id, team_id, registered_at) VALUES (?, ?, ?)', [$tid, $teamId, date('Y-m-d H:i:s', strtotime('-' . (abs($offset) + 9 - $k) . ' days'))]);
        }

        // Round-robin fixtures for tournaments that already started.
        if ($status === 'Upcoming') {
            continue;
        }
        $pairs = [];
        for ($a = 0; $a < count($regTeams); $a++) {
            for ($b = $a + 1; $b < count($regTeams); $b++) {
                $pairs[] = [$regTeams[$a], $regTeams[$b]];
            }
        }
        $perDay = ($cat === 'Football') ? 2 : 1;
        foreach ($pairs as $i => $pair) {
            $day = intdiv($i, $perDay);
            $hour = ($i % $perDay === 0) ? 15 : 18;
            $when = strtotime($start . " +$day days $hour:00");
            $state = 'Scheduled';
            if ($when + 3 * 3600 < time()) {
                $state = 'Finished';
            } elseif ($when <= time()) {
                $state = 'In Progress';
            }
            $s1 = $s2 = $winner = null;
            if ($state === 'Finished') {
                list($s1, $s2) = seed_score($cat);
                $winner = $s1 > $s2 ? $pair[0] : ($s2 > $s1 ? $pair[1] : null);
            }
            db_exec(
                'INSERT INTO matches (tournament_id, team1_id, team2_id, match_date, team1_score, team2_score, winner_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                [$tid, $pair[0], $pair[1], date('Y-m-d H:i:s', $when), $s1, $s2, $winner, $state]
            );
        }
    }

    /* ---- reviews ---- */
    $reviews = [
        [5, 'Superbly organised. Schedules were posted early and every match started on time.'],
        [5, 'Best tournament experience we have had. Live results updated within minutes.'],
        [4, 'Great competition and friendly atmosphere. Could use more seating for spectators.'],
        [4, 'Well run, fair refereeing. Would love a clearer prize breakdown next time.'],
        [5, 'Our team learned a lot. Looking forward to the next season!'],
        [3, 'Good overall but a couple of fixtures were rescheduled at the last minute.'],
        [4, 'Smooth registration process and helpful organisers.'],
        [5, 'Quality matches from start to finish. Highly recommended.'],
    ];
    foreach ($tournamentIds as $idx => $tid) {
        $n = in_array($idx, [0, 2, 3, 5, 6], true) ? 5 : 2;
        for ($k = 0; $k < $n; $k++) {
            $rv = $reviews[($idx * 3 + $k) % count($reviews)];
            db_exec(
                'INSERT INTO comments (tournament_id, user_id, comment, rating, created_at) VALUES (?, ?, ?, ?, ?)',
                [$tid, $players[($idx * 5 + $k * 3) % count($players)], $rv[1], $rv[0], date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 6) . ' days -' . mt_rand(0, 20) . ' hours'))]
            );
        }
    }

    /* ---- activity log (spread across the last week) ---- */
    $events = [
        [$org1, 'Tournament created: Dhaka Premier Cricket League 2026'],
        [$org1, 'Tournament created: Valorant Campus Showdown'],
        [$org2, 'Tournament created: Inter-University Football Cup'],
        [$players[0], 'New team formed: Dhaka Dynamos'],
        [$players[3], 'New team formed: Team Phantom'],
        [$org1, 'Match scheduled in Dhaka Premier Cricket League 2026'],
        [$players[5], 'New comment posted on Valorant Campus Showdown'],
        [$org2, 'Result updated: Chattogram Champions Trophy'],
        [null, 'New user registered: sabbir.ahmed'],
        [$org1, 'Result updated: Dhaka Premier Cricket League 2026'],
        [$players[8], 'Team Rogue Reign joined Valorant Campus Showdown'],
        [null, 'Admin updated User ID: 9 (Role: Player, Status: Active)'],
    ];
    foreach ($events as $i => $ev) {
        $ts = strtotime('-' . intdiv((count($events) - $i) * 7 * 24, count($events)) . ' hours -' . mt_rand(0, 40) . ' minutes');
        db_exec('INSERT INTO activity_log (user_id, activity_text, created_at) VALUES (?, ?, ?)', [$ev[0], $ev[1], date('Y-m-d H:i:s', $ts)]);
    }
    db_exec('INSERT INTO activity_log (user_id, activity_text, created_at) VALUES (?, ?, ?)', [$org1, 'Result updated: Valorant Campus Showdown', date('Y-m-d H:i:s', strtotime('-25 minutes'))]);

    db_exec(
        'INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)',
        ['Tanvir Hossain', 'tanvir.hossain@example.com', 'Team registration deadline', 'Hi, is there any chance to extend the registration deadline for the Football Cup by two days?', date('Y-m-d H:i:s', strtotime('-1 day'))]
    );

    $pdo->commit();
}

function seed_score($category)
{
    switch ($category) {
        case 'Cricket':
            $a = mt_rand(118, 212);
            $b = $a + (mt_rand(0, 1) ? 1 : -1) * mt_rand(4, 38);
            return [$a, $b];
        case 'Football':
            return [mt_rand(0, 4), mt_rand(0, 3)];
        case 'E-Sports':
            return mt_rand(0, 1) ? [2, mt_rand(0, 1)] : [mt_rand(0, 1), 2];
        case 'Badminton':
            return mt_rand(0, 1) ? [2, mt_rand(0, 1)] : [mt_rand(0, 1), 2];
        case 'Volleyball':
            return mt_rand(0, 1) ? [3, mt_rand(0, 2)] : [mt_rand(0, 2), 3];
        default:
            $a = mt_rand(55, 98);
            return [$a, $a + (mt_rand(0, 1) ? 1 : -1) * mt_rand(2, 14)];
    }
}

function run_setup($reset = false)
{
    if ($reset) {
        setup_reset();
    }
    setup_schema();
    if ((int) db_val('SELECT COUNT(*) FROM users') === 0) {
        setup_seed();
        return 'Schema created and demo data seeded.';
    }
    return 'Schema is up to date; data already present.';
}

if (PHP_SAPI === 'cli' && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $reset = in_array('--reset', $argv, true);
    echo run_setup($reset) . PHP_EOL;
}
