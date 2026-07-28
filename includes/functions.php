<?php
/**
 * KINMEL E-Commerce System — Shared helper functions
 */

declare(strict_types=1);

/**
 * Escape output for HTML context (XSS-safe display).
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Build an absolute URL from a path relative to BASE_URL.
 */
function url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Redirect and stop execution.
 */
function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

/**
 * Format a money amount for display.
 */
function format_money(float|int|string $amount): string
{
    return CURRENCY_SYMBOL . ' ' . number_format((float) $amount, 2);
}

/**
 * Store a one-time flash message in the session.
 */
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Read and clear the flash message (if any).
 *
 * @return array{type: string, message: string}|null
 */
function get_flash(): ?array
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

/**
 * Whether a flash message is currently waiting.
 */
function has_flash(): bool
{
    return !empty($_SESSION['flash']);
}
