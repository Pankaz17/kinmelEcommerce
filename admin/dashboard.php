<?php
/**
 * KINMEL E-Commerce System — Admin dashboard (protected)
 */

declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';
require dirname(__DIR__) . '/config/session.php';
require dirname(__DIR__) . '/includes/functions.php';
require dirname(__DIR__) . '/includes/auth.php';

requireAdmin();

$user = currentUser();
$pageTitle = 'Admin Dashboard — ' . SITE_NAME;
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="mx-auto max-w-4xl px-4 py-12">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium uppercase tracking-wider text-ink/45">Administration</p>
            <h1 class="mt-1 font-display text-3xl font-semibold tracking-tight text-ink">Welcome Admin</h1>
            <p class="mt-2 text-ink/65">
                Logged in as <span class="font-semibold text-moss"><?= e($user['name'] ?? '') ?></span>
            </p>
        </div>
        <a
            href="<?= e(url('admin/logout.php')) ?>"
            class="rounded-md bg-ink px-4 py-2 text-sm font-semibold text-sand transition hover:bg-moss"
        >
            Log out
        </a>
    </div>

    <div class="mt-10 grid gap-4 sm:grid-cols-2">
        <div class="border border-dashed border-ink/20 bg-white/50 px-5 py-6">
            <p class="text-sm font-semibold text-ink/40">Coming next</p>
            <p class="mt-2 text-ink/70">Product &amp; category management will appear here in a later phase.</p>
        </div>
        <div class="border border-dashed border-ink/20 bg-white/50 px-5 py-6">
            <p class="text-sm font-semibold text-ink/40">Coming next</p>
            <p class="mt-2 text-ink/70">Orders and reporting will appear here in a later phase.</p>
        </div>
    </div>

    <p class="mt-8 text-sm text-ink/50">
        <a href="<?= e(url('index.php')) ?>" class="font-medium text-moss hover:text-leaf">View storefront</a>
    </p>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
