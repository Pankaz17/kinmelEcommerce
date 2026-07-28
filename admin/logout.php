<?php
/**
 * KINMEL E-Commerce System — Admin logout
 */

declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';
require dirname(__DIR__) . '/config/session.php';
require dirname(__DIR__) . '/includes/functions.php';
require dirname(__DIR__) . '/includes/auth.php';

logoutUser();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_flash('success', 'Admin session ended.');
redirect('admin/login.php');
