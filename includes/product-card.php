<?php
/**
 * KINMEL E-Commerce System — Reusable product card
 *
 * Expects: $product array with keys:
 * id, name, price, stock, image, category_name
 */

declare(strict_types=1);

$product = $product ?? [];
$productId = (int) ($product['id'] ?? 0);
$name = (string) ($product['name'] ?? '');
$categoryName = (string) ($product['category_name'] ?? '');
$price = $product['price'] ?? 0;
$stock = (int) ($product['stock'] ?? 0);
$imageUrl = product_image_url($product['image'] ?? null);
$inStock = $stock > 0;
$detailsUrl = url('customer/product-details.php?id=' . $productId);
?>
<article class="flex h-full flex-col border border-ink/10 bg-white/70 transition hover:border-moss/35">
    <a href="<?= e($detailsUrl) ?>" class="block overflow-hidden bg-moss/5">
        <?php if ($imageUrl !== ''): ?>
            <img
                src="<?= e($imageUrl) ?>"
                alt="<?= e($name) ?>"
                class="aspect-[4/3] w-full object-cover transition duration-300 hover:scale-[1.02]"
                loading="lazy"
            >
        <?php else: ?>
            <div class="flex aspect-[4/3] w-full items-center justify-center text-sm text-moss/50">
                No image
            </div>
        <?php endif; ?>
    </a>

    <div class="flex flex-1 flex-col p-4">
        <p class="text-xs font-medium uppercase tracking-wider text-ink/45"><?= e($categoryName) ?></p>
        <h3 class="mt-1 font-display text-lg font-semibold leading-snug text-ink">
            <a href="<?= e($detailsUrl) ?>" class="hover:text-moss"><?= e($name) ?></a>
        </h3>
        <p class="mt-2 text-base font-semibold text-moss"><?= e(format_money($price)) ?></p>
        <p class="mt-1 text-sm <?= $inStock ? 'text-emerald-800' : 'text-red-700' ?>">
            <?= $inStock ? 'In stock (' . e((string) $stock) . ')' : 'Out of stock' ?>
        </p>
        <div class="mt-auto pt-4">
            <a
                href="<?= e($detailsUrl) ?>"
                class="inline-flex w-full items-center justify-center rounded-md bg-moss px-3 py-2 text-sm font-semibold text-sand transition hover:bg-leaf"
            >
                View details
            </a>
        </div>
    </div>
</article>
