<?php
/**
 * KINMEL E-Commerce System — Admin: list products
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
require $root . '/config/config.php';
require $root . '/config/session.php';
require $root . '/config/database.php';
require $root . '/includes/functions.php';
require $root . '/includes/auth.php';

requireAdmin();

$products = db()->query(
    'SELECT p.id, p.name, p.price, p.stock, p.image, p.status,
            c.name AS category_name
     FROM products p
     INNER JOIN categories c ON c.id = p.category_id
     ORDER BY p.created_at DESC, p.id DESC'
)->fetchAll();

$pageTitle = 'Products — Admin — ' . SITE_NAME;
require $root . '/includes/header.php';
?>

<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium uppercase tracking-wider text-ink/45">Catalog</p>
            <h1 class="mt-1 font-display text-3xl font-semibold text-ink">Products</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= e(url('admin/dashboard.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2 text-sm font-semibold text-ink hover:border-moss/40">Dashboard</a>
            <a href="<?= e(url('admin/products/create.php')) ?>" class="rounded-md bg-moss px-4 py-2 text-sm font-semibold text-sand hover:bg-leaf">Add product</a>
        </div>
    </div>

    <div class="mt-8 overflow-x-auto border border-ink/10 bg-white/70">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-ink/10 bg-ink/5 text-xs uppercase tracking-wider text-ink/55">
                <tr>
                    <th class="px-4 py-3 font-semibold">Image</th>
                    <th class="px-4 py-3 font-semibold">Name</th>
                    <th class="px-4 py-3 font-semibold">Category</th>
                    <th class="px-4 py-3 font-semibold">Price</th>
                    <th class="px-4 py-3 font-semibold">Stock</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products === []): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-ink/50">No products yet. Add a category first, then create products.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <?php $imgUrl = product_image_url($product['image'] ?? null); ?>
                        <tr class="border-b border-ink/5 align-middle hover:bg-sand/60">
                            <td class="px-4 py-3">
                                <?php if ($imgUrl !== ''): ?>
                                    <img src="<?= e($imgUrl) ?>" alt="" class="h-12 w-12 object-cover" width="48" height="48">
                                <?php else: ?>
                                    <span class="inline-flex h-12 w-12 items-center justify-center bg-ink/5 text-[10px] text-ink/40">No image</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-medium text-ink"><?= e($product['name']) ?></td>
                            <td class="px-4 py-3 text-ink/70"><?= e($product['category_name']) ?></td>
                            <td class="px-4 py-3 text-ink/80"><?= e(format_money($product['price'])) ?></td>
                            <td class="px-4 py-3 text-ink/70"><?= e((string) $product['stock']) ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded border px-2 py-0.5 text-xs font-medium <?= e(status_badge_class((string) $product['status'])) ?>">
                                    <?= e($product['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="<?= e(url('admin/products/edit.php?id=' . (int) $product['id'])) ?>" class="font-semibold text-moss hover:text-leaf">Edit</a>
                                <span class="text-ink/25">·</span>
                                <a href="<?= e(url('admin/products/delete.php?id=' . (int) $product['id'])) ?>" class="font-semibold text-red-700 hover:text-red-800">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require $root . '/includes/footer.php'; ?>
