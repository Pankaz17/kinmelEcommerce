<?php
/**
 * KINMEL E-Commerce System — Admin: list categories
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
require $root . '/config/config.php';
require $root . '/config/session.php';
require $root . '/config/database.php';
require $root . '/includes/functions.php';
require $root . '/includes/auth.php';

requireAdmin();

$categories = db()->query(
    'SELECT c.id, c.name, c.description, c.status, c.created_at,
            (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS product_count
     FROM categories c
     ORDER BY c.name ASC'
)->fetchAll();

$pageTitle = 'Categories — Admin — ' . SITE_NAME;
require $root . '/includes/header.php';
?>

<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium uppercase tracking-wider text-ink/45">Catalog</p>
            <h1 class="mt-1 font-display text-3xl font-semibold text-ink">Categories</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= e(url('admin/dashboard.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2 text-sm font-semibold text-ink hover:border-moss/40">Dashboard</a>
            <a href="<?= e(url('admin/categories/create.php')) ?>" class="rounded-md bg-moss px-4 py-2 text-sm font-semibold text-sand hover:bg-leaf">Add category</a>
        </div>
    </div>

    <div class="mt-8 overflow-x-auto border border-ink/10 bg-white/70">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-ink/10 bg-ink/5 text-xs uppercase tracking-wider text-ink/55">
                <tr>
                    <th class="px-4 py-3 font-semibold">Name</th>
                    <th class="px-4 py-3 font-semibold">Description</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Products</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories === []): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-ink/50">No categories yet. Create one to get started.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr class="border-b border-ink/5 align-top hover:bg-sand/60">
                            <td class="px-4 py-3 font-medium text-ink"><?= e($category['name']) ?></td>
                            <td class="px-4 py-3 text-ink/70"><?= e(mb_strimwidth((string) ($category['description'] ?? ''), 0, 80, '…')) ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded border px-2 py-0.5 text-xs font-medium <?= e(status_badge_class((string) $category['status'])) ?>">
                                    <?= e($category['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-ink/70"><?= e((string) $category['product_count']) ?></td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="<?= e(url('admin/categories/edit.php?id=' . (int) $category['id'])) ?>" class="font-semibold text-moss hover:text-leaf">Edit</a>
                                <span class="text-ink/25">·</span>
                                <a href="<?= e(url('admin/categories/delete.php?id=' . (int) $category['id'])) ?>" class="font-semibold text-red-700 hover:text-red-800">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require $root . '/includes/footer.php'; ?>
