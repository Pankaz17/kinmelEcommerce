<?php
/**
 * KINMEL E-Commerce System — Primary navigation
 */

declare(strict_types=1);

$navUser = function_exists('currentUser') ? currentUser() : null;
$isAdmin = function_exists('checkAdmin') && checkAdmin();
?>
<header class="border-b border-ink/10 bg-sand/90 backdrop-blur-sm">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
        <a href="<?= e(url('index.php')) ?>" class="group flex items-baseline gap-2">
            <span class="font-display text-2xl font-bold tracking-tight text-moss transition group-hover:text-leaf">
                KINMEL
            </span>
            <span class="hidden text-xs font-medium uppercase tracking-[0.18em] text-ink/50 sm:inline">
                E-Commerce
            </span>
        </a>

        <nav class="flex flex-wrap items-center justify-end gap-1 text-sm font-medium sm:gap-2" aria-label="Primary">
            <a href="<?= e(url('index.php')) ?>" class="rounded-md px-3 py-2 text-ink/80 transition hover:bg-moss/10 hover:text-moss">
                Home
            </a>
            <a href="<?= e(url('customer/products.php')) ?>" class="rounded-md px-3 py-2 text-ink/80 transition hover:bg-moss/10 hover:text-moss">
                Products
            </a>

            <?php if ($navUser !== null): ?>
                <?php if ($isAdmin): ?>
                    <a href="<?= e(url('admin/dashboard.php')) ?>" class="rounded-md px-3 py-2 text-ink/80 transition hover:bg-moss/10 hover:text-moss">
                        Dashboard
                    </a>
                    <a href="<?= e(url('admin/categories/index.php')) ?>" class="rounded-md px-3 py-2 text-ink/80 transition hover:bg-moss/10 hover:text-moss">
                        Categories
                    </a>
                    <a href="<?= e(url('admin/products/index.php')) ?>" class="rounded-md px-3 py-2 text-ink/80 transition hover:bg-moss/10 hover:text-moss">
                        Products
                    </a>
                    <a href="<?= e(url('admin/logout.php')) ?>" class="rounded-md bg-ink px-3 py-2 text-sand transition hover:bg-moss">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?= e(url('customer/profile.php')) ?>" class="rounded-md px-3 py-2 text-ink/80 transition hover:bg-moss/10 hover:text-moss">
                        <?= e($navUser['name']) ?>
                    </a>
                    <a href="<?= e(url('customer/logout.php')) ?>" class="rounded-md bg-moss px-3 py-2 text-sand transition hover:bg-leaf">
                        Logout
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= e(url('customer/login.php')) ?>" class="rounded-md px-3 py-2 text-ink/80 transition hover:bg-moss/10 hover:text-moss">
                    Login
                </a>
                <a href="<?= e(url('customer/register.php')) ?>" class="rounded-md bg-moss px-3 py-2 text-sand transition hover:bg-leaf">
                    Register
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>
