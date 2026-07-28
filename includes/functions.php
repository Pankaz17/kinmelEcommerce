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

/**
 * Public URL for a stored product image filename (or empty string).
 */
function product_image_url(?string $filename): string
{
    if ($filename === null || $filename === '') {
        return '';
    }

    return PRODUCT_UPLOAD_URL . rawurlencode($filename);
}

/**
 * Delete a product image file from disk if it exists.
 */
function delete_product_image_file(?string $filename): void
{
    if ($filename === null || $filename === '') {
        return;
    }

    // Prevent path traversal — only basename under the products upload dir
    $safe = basename($filename);
    $path = PRODUCT_UPLOAD_PATH . $safe;

    if (is_file($path)) {
        unlink($path);
    }
}

/**
 * Validate and store an uploaded product image.
 *
 * @param array $file A single $_FILES[...] entry
 * @return array{ok: true, filename: string}|array{ok: false, error: string}
 */
function upload_product_image(array $file): array
{
    if (!isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'error' => 'Please choose an image to upload.'];
    }

    if ((int) $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Image upload failed. Please try again.'];
    }

    if (!isset($file['size']) || (int) $file['size'] <= 0) {
        return ['ok' => false, 'error' => 'The uploaded file is empty.'];
    }

    if ((int) $file['size'] > MAX_UPLOAD_SIZE) {
        return ['ok' => false, 'error' => 'Image must be 2 MB or smaller.'];
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return ['ok' => false, 'error' => 'Invalid upload.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: '';

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    if (!in_array($mime, ALLOWED_IMAGE_TYPES, true) || !isset($extensions[$mime])) {
        return ['ok' => false, 'error' => 'Allowed image types: JPG, PNG, WEBP, GIF.'];
    }

    if (!is_dir(PRODUCT_UPLOAD_PATH) && !mkdir(PRODUCT_UPLOAD_PATH, 0755, true) && !is_dir(PRODUCT_UPLOAD_PATH)) {
        return ['ok' => false, 'error' => 'Upload directory is not available.'];
    }

    $filename = 'product_' . bin2hex(random_bytes(8)) . '.' . $extensions[$mime];
    $destination = PRODUCT_UPLOAD_PATH . $filename;

    if (!move_uploaded_file($tmp, $destination)) {
        return ['ok' => false, 'error' => 'Could not save the uploaded image.'];
    }

    return ['ok' => true, 'filename' => $filename];
}

/**
 * Tailwind classes for active/inactive status badges.
 */
function status_badge_class(string $status): string
{
    return $status === 'active'
        ? 'bg-emerald-50 text-emerald-800 border-emerald-600/20'
        : 'bg-slate-100 text-slate-600 border-slate-400/30';
}
