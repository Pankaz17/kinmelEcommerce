<?php
/**
 * KINMEL E-Commerce System — Customer profile (protected)
 */

declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';
require dirname(__DIR__) . '/config/session.php';
require dirname(__DIR__) . '/includes/functions.php';
require dirname(__DIR__) . '/includes/auth.php';

requireLogin();

$user = currentUser();
$pageTitle = 'My Profile — ' . SITE_NAME;
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="mx-auto max-w-3xl px-4 py-12">
    <h1 class="font-display text-3xl font-semibold tracking-tight text-ink">My profile</h1>
    <p class="mt-2 text-ink/65">You are signed in to your customer account.</p>

    <div class="mt-8 border border-ink/10 bg-white/60 px-6 py-6">
        <p class="text-sm uppercase tracking-wider text-ink/45">Welcome</p>
        <p class="mt-2 font-display text-2xl font-semibold text-moss"><?= e($user['name'] ?? '') ?></p>
        <dl class="mt-6 space-y-3 text-sm">
            <div class="flex flex-wrap gap-2">
                <dt class="font-medium text-ink/55">User ID</dt>
                <dd class="text-ink"><?= e((string) ($user['id'] ?? '')) ?></dd>
            </div>
            <div class="flex flex-wrap gap-2">
                <dt class="font-medium text-ink/55">Role</dt>
                <dd class="text-ink"><?= e($user['role'] ?? '') ?></dd>
            </div>
        </dl>

        <div class="mt-8 flex flex-wrap gap-3">
            <a
                href="<?= e(url('index.php')) ?>"
                class="rounded-md border border-ink/15 bg-white px-4 py-2 text-sm font-semibold text-ink transition hover:border-moss/40"
            >
                Back to store
            </a>
            <a
                href="<?= e(url('customer/logout.php')) ?>"
                class="rounded-md bg-moss px-4 py-2 text-sm font-semibold text-sand transition hover:bg-leaf"
            >
                Log out
            </a>
        </div>
    </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
