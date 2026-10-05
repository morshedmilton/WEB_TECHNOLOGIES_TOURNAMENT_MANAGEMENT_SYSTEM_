-- TourneyHub reference schema (MySQL 8 / TiDB / Aiven).
-- You normally do NOT need to run this by hand: `php scripts/setup.php`
-- (run automatically on container start) creates the same tables and seeds demo data.
-- No foreign keys on purpose: cascading deletes are handled transactionally in the
-- model layer so the schema works on every MySQL-compatible cloud flavour.

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,           -- bcrypt via password_hash()
  role VARCHAR(20) NOT NULL DEFAULT 'Player',    -- Admin | Organizer | Player
  status VARCHAR(20) NOT NULL DEFAULT 'Active',  -- Active | Blocked
  profile_picture VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tournaments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  category VARCHAR(50) NOT NULL,
  description TEXT,
  banner_image VARCHAR(255) DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Upcoming', -- Upcoming | Ongoing | Completed
  location VARCHAR(150) DEFAULT NULL,
  start_date DATE DEFAULT NULL,
  prize_pool VARCHAR(60) DEFAULT NULL,
  max_teams INT NOT NULL DEFAULT 8,
  created_by INT DEFAULT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_tournaments_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS teams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  sport VARCHAR(50) NOT NULL,
  created_by INT DEFAULT NULL,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS team_members (
  team_id INT NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY (team_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tournament_registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tournament_id INT NOT NULL,
  team_id INT NOT NULL,
  registered_at DATETIME NOT NULL,
  UNIQUE (tournament_id, team_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS matches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tournament_id INT NOT NULL,
  team1_id INT NOT NULL,
  team2_id INT NOT NULL,
  match_date DATETIME NOT NULL,
  team1_score INT DEFAULT NULL,
  team2_score INT DEFAULT NULL,
  winner_id INT DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Scheduled', -- Scheduled | In Progress | Finished
  INDEX idx_matches_tournament (tournament_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tournament_id INT NOT NULL,
  user_id INT NOT NULL,
  comment TEXT NOT NULL,
  rating INT NOT NULL DEFAULT 5,
  created_at DATETIME NOT NULL,
  INDEX idx_comments_tournament (tournament_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attachments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tournament_id INT NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  file_type VARCHAR(50) DEFAULT NULL,
  uploaded_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS activity_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  activity_text VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_activity_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  subject VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
