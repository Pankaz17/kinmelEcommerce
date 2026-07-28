<?php
/**
 * KINMEL E-Commerce System — Application configuration
 */

declare(strict_types=1);

// Site identity
define('SITE_NAME', 'KINMEL E-Commerce');
define('SITE_TAGLINE', 'Shop smarter. Live better.');

/**
 * Base URL — adjust to match your local setup.
 * Examples:
 *   http://localhost/kinmelEcommerce/
 *   http://kinmelEcommerce.test/
 */
define('BASE_URL', 'http://localhost/kinmelEcommerce/');

// Currency display
define('CURRENCY_CODE', 'NPR');
define('CURRENCY_SYMBOL', 'Rs.');

// Timezone
date_default_timezone_set('Asia/Kathmandu');

// Paths (filesystem)
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('UPLOAD_PATH', ROOT_PATH . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
define('PRODUCT_UPLOAD_PATH', UPLOAD_PATH . 'products' . DIRECTORY_SEPARATOR);

// Public upload URLs
define('UPLOAD_URL', BASE_URL . 'assets/uploads/');
define('PRODUCT_UPLOAD_URL', UPLOAD_URL . 'products/');

// Upload limits (foundation defaults for later phases)
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
