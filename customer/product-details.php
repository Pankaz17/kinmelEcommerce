<?php
/**
 * KINMEL E-Commerce System — Customer product details
 */

declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';
require dirname(__DIR__) . '/config/session.php';
require dirname(__DIR__) . '/config/database.php';
require dirname(__DIR__) . '/includes/functions.php';
require dirname(__DIR__) . '/includes/auth.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    set_flash('error', 'Invalid product.');
    redirect('customer/products.php');
}

$stmt = db()->prepare(
    "SELECT p.id, p.name, p.description, p.price, p.stock, p.image, p.status,
            c.id AS category_id, c.name AS category_name
     FROM products p
     INNER JOIN categories c ON c.id = p.category_id
     WHERE p.id = ? AND p.status = 'active'
     LIMIT 1"
);
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('error', 'Product not found or unavailable.');
    redirect('customer/products.php');
}

$imageUrl = product_image_url($product['image'] ?? null);
$stock = (int) $product['stock'];
$inStock = $stock > 0;
$availability = $inStock ? 'In stock' : 'Out of stock';

$pageTitle = $product['name'] . ' — ' . SITE_NAME;
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="mx-auto max-w-6xl px-4 py-10">
    <p class="text-sm text-ink/55">
        <a href="<?= e(url('customer/products.php')) ?>" class="font-medium text-moss hover:text-leaf">Products</a>
        <span class="mx-1 text-ink/30">/</span>
        <a
            href="<?= e(url('customer/products.php?category=' . (int) $product['category_id'])) ?>"
            class="font-medium text-moss hover:text-leaf"
        ><?= e($product['category_name']) ?></a>
        <span class="mx-1 text-ink/30">/</span>
        <span class="text-ink/70"><?= e($product['name']) ?></span>
    </p>

    <div class="mt-8 grid gap-10 lg:grid-cols-2 lg:items-start">
        <div class="overflow-hidden border border-ink/10 bg-moss/5">
            <?php if ($imageUrl !== ''): ?>
                <img
                    src="<?= e($imageUrl) ?>"
                    alt="<?= e($product['name']) ?>"
                    class="aspect-square w-full object-cover lg:aspect-[4/3]"
                >
            <?php else: ?>
                <div class="flex aspect-square w-full items-center justify-center text-moss/50 lg:aspect-[4/3]">
                    No image available
                </div>
            <?php endif; ?>
        </div>

        <div>
            <p class="text-sm font-medium uppercase tracking-wider text-ink/45"><?= e($product['category_name']) ?></p>
            <h1 class="mt-2 font-display text-3xl font-semibold tracking-tight text-ink sm:text-4xl">
                <?= e($product['name']) ?>
            </h1>
            <p class="mt-4 text-2xl font-semibold text-moss"><?= e(format_money($product['price'])) ?></p>

            <dl class="mt-6 space-y-3 border-y border-ink/10 py-5 text-sm">
                <div class="flex flex-wrap gap-x-3 gap-y-1">
                    <dt class="w-28 font-medium text-ink/55">Category</dt>
                    <dd class="text-ink"><?= e($product['category_name']) ?></dd>
                </div>
                <div class="flex flex-wrap gap-x-3 gap-y-1">
                    <dt class="w-28 font-medium text-ink/55">Stock</dt>
                    <dd class="text-ink"><?= e((string) $stock) ?> unit(s)</dd>
                </div>
                <div class="flex flex-wrap gap-x-3 gap-y-1">
                    <dt class="w-28 font-medium text-ink/55">Availability</dt>
                    <dd class="<?= $inStock ? 'font-semibold text-emerald-800' : 'font-semibold text-red-700' ?>">
                        <?= e($availability) ?>
                    </dd>
                </div>
            </dl>

            <div class="mt-6">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-ink/45">Description</h2>
                <p class="mt-2 whitespace-pre-line text-base leading-relaxed text-ink/75">
                    <?php if (trim((string) ($product['description'] ?? '')) !== ''): ?>
                        <?= e($product['description']) ?>
                    <?php else: ?>
                        No description provided for this product.
                    <?php endif; ?>
                </p>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a
                    href="<?= e(url('customer/products.php')) ?>"
                    class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40"
                >
                    Back to products
                </a>
                <a
                    href="<?= e(url('customer/products.php?category=' . (int) $product['category_id'])) ?>"
                    class="rounded-md bg-moss px-4 py-2.5 text-sm font-semibold text-sand hover:bg-leaf"
                >
                    More in <?= e($product['category_name']) ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
