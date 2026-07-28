<?php
/**
 * KINMEL E-Commerce System — Authentication helpers
 *
 * Requires session already started (config/session.php).
 */

declare(strict_types=1);

/**
 * Whether the current session belongs to a logged-in user.
 */
function checkLogin(): bool
{
    return isset($_SESSION['user_id'])
        && is_numeric($_SESSION['user_id'])
        && (int) $_SESSION['user_id'] > 0;
}

/**
 * Whether the current session belongs to an administrator.
 */
function checkAdmin(): bool
{
    return checkLogin()
        && isset($_SESSION['user_role'])
        && $_SESSION['user_role'] === 'admin';
}

/**
 * Require a logged-in user; otherwise redirect to customer login.
 */
function requireLogin(): void
{
    if (!checkLogin()) {
        set_flash('error', 'Please log in to continue.');
        redirect('customer/login.php');
    }
}

/**
 * Require an admin session; otherwise redirect to admin login.
 */
function requireAdmin(): void
{
    if (!checkAdmin()) {
        set_flash('error', 'Admin access required. Please log in.');
        redirect('admin/login.php');
    }
}

/**
 * Current user data from the session (null if guest).
 *
 * @return array{id: int, name: string, role: string}|null
 */
function currentUser(): ?array
{
    if (!checkLogin()) {
        return null;
    }

    return [
        'id'   => (int) $_SESSION['user_id'],
        'name' => (string) ($_SESSION['user_name'] ?? ''),
        'role' => (string) ($_SESSION['user_role'] ?? ''),
    ];
}

/**
 * Persist authenticated user details in the session.
 */
function loginUser(int $id, string $name, string $role): void
{
    session_regenerate_id(true);

    $_SESSION['user_id']   = $id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_role'] = $role;
}

/**
 * Clear authentication-related session keys and invalidate the session.
 */
function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            (bool) $params['secure'],
            (bool) $params['httponly']
        );
    }

    session_destroy();
}
