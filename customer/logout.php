<?php
/**
 * KINMEL E-Commerce System — Customer logout
 */

declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';
require dirname(__DIR__) . '/config/session.php';
require dirname(__DIR__) . '/includes/functions.php';
require dirname(__DIR__) . '/includes/auth.php';

logoutUser();

// session.php will not re-execute after require; start a fresh session for flash
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_flash('success', 'You have been logged out.');
redirect('customer/login.php');
