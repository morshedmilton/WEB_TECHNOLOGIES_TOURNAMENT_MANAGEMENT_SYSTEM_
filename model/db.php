<?php
/**
 * Database layer (PDO).
 *
 * Production runs on a managed cloud MySQL (TiDB Cloud, Aiven, PlanetScale,
 * Railway, AWS RDS ...). Everything is configured through environment
 * variables, so no credentials live in the repository:
 *
 *   DB_DRIVER   mysql (default) | sqlite   (sqlite = zero-setup local demo)
 *   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
 *   DB_SSL      true|false  -> enable TLS (required by most cloud providers)
 *   DB_SSL_CA   optional path to a CA bundle
 *   APP_TIMEZONE  default Asia/Dhaka
 */

function load_env_file($path)
{
    if (!is_readable($path)) {
        return;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\"'");
        if (getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

function env_value($key, $default = null)
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return $value;
}

load_env_file(__DIR__ . '/../.env');
date_default_timezone_set(env_value('APP_TIMEZONE', 'Asia/Dhaka'));

function db()
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    if (db_driver() === 'sqlite') {
        $file = env_value('SQLITE_PATH', __DIR__ . '/../storage/tournament.sqlite');
        $pdo = new PDO('sqlite:' . $file, null, null, $options);
        $pdo->exec('PRAGMA foreign_keys = ON');
        return $pdo;
    }

    $host = env_value('DB_HOST', '127.0.0.1');
    $port = env_value('DB_PORT', '3306');
    $name = env_value('DB_NAME', 'tournament_db');

    if (filter_var(env_value('DB_SSL', 'false'), FILTER_VALIDATE_BOOLEAN)) {
        $ca = env_value('DB_SSL_CA');
        if (!$ca) {
            foreach (['/etc/ssl/certs/ca-certificates.crt', '/etc/pki/tls/certs/ca-bundle.crt', '/etc/ssl/cert.pem'] as $candidate) {
                if (is_readable($candidate)) {
                    $ca = $candidate;
                    break;
                }
            }
        }
        if ($ca) {
            $options[defined('Pdo\\Mysql::ATTR_SSL_CA') ? constant('Pdo\\Mysql::ATTR_SSL_CA') : PDO::MYSQL_ATTR_SSL_CA] = $ca;
        }
        $options[defined('Pdo\\Mysql::ATTR_SSL_VERIFY_SERVER_CERT') ? constant('Pdo\\Mysql::ATTR_SSL_VERIFY_SERVER_CERT') : PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = (bool) $ca;
    }

    $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
    $pdo = new PDO($dsn, env_value('DB_USER', 'root'), env_value('DB_PASS', ''), $options);
    return $pdo;
}

function db_driver()
{
    return strtolower(env_value('DB_DRIVER', 'mysql'));
}

/** Run a prepared statement and return the statement. */
function db_run($sql, $params = [])
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/** All rows. */
function db_all($sql, $params = [])
{
    return db_run($sql, $params)->fetchAll();
}

/** First row or null. */
function db_one($sql, $params = [])
{
    $row = db_run($sql, $params)->fetch();
    return $row === false ? null : $row;
}

/** First column of the first row. */
function db_val($sql, $params = [])
{
    $row = db_run($sql, $params)->fetch(PDO::FETCH_NUM);
    return $row === false ? null : $row[0];
}

/** Execute and return the number of affected rows. */
function db_exec($sql, $params = [])
{
    return db_run($sql, $params)->rowCount();
}

/** Insert and return the new auto-increment id. */
function db_insert($sql, $params = [])
{
    db_run($sql, $params);
    return (int) db()->lastInsertId();
}

/** Current timestamp in the app timezone, as a DB-friendly string. */
function now()
{
    return date('Y-m-d H:i:s');
}

/** For sqlite/mysql portable "table exists" checks. */
function db_table_exists($table)
{
    try {
        db_run("SELECT 1 FROM $table LIMIT 1");
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
