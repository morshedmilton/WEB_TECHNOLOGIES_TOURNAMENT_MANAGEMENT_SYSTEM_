<?php
$host = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "tournament_db";
function getConnection(){
    global $host, $dbuser, $dbpass, $dbname;
    $con = mysqli_connect($host, $dbuser, $dbpass, $dbname);
    return $con;
}

/**
 * ============================================
 * @author SHAHRIAR
 * @task Feature 16: Security - Secure Database Connection
 * @date 2025-12-25
 * ============================================
 */
?>
