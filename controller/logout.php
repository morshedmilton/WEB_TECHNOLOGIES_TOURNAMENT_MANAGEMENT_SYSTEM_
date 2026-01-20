<?php
session_start();

// Remove session variable
unset($_SESSION['username']);
session_destroy();

// Remove status cookie (by reducing time)
setcookie('status', 'true', time() - 3600, '/');

// Redirect to login page
header('location: ../view/login.php');

/**
 * ============================================
 * @author morshedmilton
 * @task Feature 1: Authentication - Session Destroy and Logout
 * @date 2025-12-31
 * ============================================
 */
?>