<?php
/**
 * KINMEL E-Commerce System — Admin: delete product
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
require $root . '/config/config.php';
require $root . '/config/session.php';
require $root . '/config/database.php';
require $root . '/includes/functions.php';
require $root . '/includes/auth.php';

requireAdmin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id) {
    set_flash('error', 'Invalid product.');
    redirect('admin/products/index.php');
}

$stmt = db()->prepare('SELECT id, name, image FROM products WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('error', 'Product not found.');
    redirect('admin/products/index.php');
}

$orderCheck = db()->prepare('SELECT COUNT(*) FROM order_items WHERE product_id = ?');
$orderCheck->execute([$id]);
$orderItemCount = (int) $orderCheck->fetchColumn();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($orderItemCount > 0) {
        $errors[] = 'Cannot delete this product because it appears in existing orders. Set status to inactive instead.';
    } else {
        try {
            $pdo = db();
            $pdo->beginTransaction();

            // Remove cart rows first (also cascades via FK, but explicit is clearer)
            $pdo->prepare('DELETE FROM cart_items WHERE product_id = ?')->execute([$id]);
            $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);

            $pdo->commit();

            delete_product_image_file($product['image'] ?? null);

            set_flash('success', 'Product deleted successfully.');
            redirect('admin/products/index.php');
        } catch (Throwable $e) {
            if (db()->inTransaction()) {
                db()->rollBack();
            }
            $errors[] = 'Could not delete product. It may be referenced by other records.';
        }
    }
}

$pageTitle = 'Delete Product — Admin — ' . SITE_NAME;
require $root . '/includes/header.php';
?>

<section class="mx-auto max-w-xl px-4 py-10">
    <h1 class="font-display text-3xl font-semibold text-ink">Delete product</h1>

    <?php if ($errors !== []): ?>
        <div class="mt-6 space-y-1 rounded-md border border-red-600/30 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($orderItemCount > 0): ?>
        <div class="mt-6 rounded-md border border-amber-600/30 bg-amber-50 px-4 py-3 text-sm text-amber-950" role="status">
            <p>
                <strong><?= e($product['name']) ?></strong> is linked to
                <strong><?= e((string) $orderItemCount) ?></strong> order line(s).
                Deletion is blocked to preserve order history.
            </p>
        </div>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="<?= e(url('admin/products/edit.php?id=' . $id)) ?>" class="rounded-md bg-moss px-4 py-2.5 text-sm font-semibold text-sand hover:bg-leaf">Edit / set inactive</a>
            <a href="<?= e(url('admin/products/index.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40">Back</a>
        </div>
    <?php else: ?>
        <p class="mt-4 text-ink/70">
            Are you sure you want to delete <strong class="text-ink"><?= e($product['name']) ?></strong>?
            The product image file will also be removed.
        </p>
        <form method="post" action="<?= e(url('admin/products/delete.php?id=' . $id)) ?>" class="mt-8 flex flex-wrap gap-3">
            <input type="hidden" name="id" value="<?= e((string) $id) ?>">
            <button type="submit" class="rounded-md bg-red-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-800">Yes, delete</button>
            <a href="<?= e(url('admin/products/index.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40">Cancel</a>
        </form>
    <?php endif; ?>
</section>

<?php require $root . '/includes/footer.php'; ?>
