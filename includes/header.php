<?php
/**
 * KINMEL E-Commerce System — Shared page header
 *
 * Expects optional: $pageTitle (string)
 */

declare(strict_types=1);

if (!function_exists('currentUser')) {
    require ROOT_PATH . 'includes/auth.php';
}

$pageTitle = $pageTitle ?? SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e(SITE_NAME) ?> — academic e-commerce storefront">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#14201b',
                        moss: '#1f4d3a',
                        leaf: '#2f6b4f',
                        sand: '#f3eee6',
                        clay: '#c45c26',
                    },
                    fontFamily: {
                        display: ['Fraunces', 'Georgia', 'serif'],
                        sans: ['DM Sans', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
</head>
<body class="min-h-screen bg-sand font-sans text-ink antialiased">
<?php require ROOT_PATH . 'includes/navbar.php'; ?>
<?php require ROOT_PATH . 'includes/flash_message.php'; ?>
<main>
