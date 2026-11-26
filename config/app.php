<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('oda_session');
    session_start();
}
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
