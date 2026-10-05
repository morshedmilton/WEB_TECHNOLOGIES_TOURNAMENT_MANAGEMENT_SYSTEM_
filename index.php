<?php
require_once __DIR__ . '/model/helpers.php';
header('Location: ' . (is_logged_in() ? 'view/home.php' : 'view/landing.php'));
exit();
