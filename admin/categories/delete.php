<?php
/**
 * KINMEL E-Commerce System — Admin: delete category
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
    set_flash('error', 'Invalid category.');
    redirect('admin/categories/index.php');
}

$stmt = db()->prepare('SELECT id, name FROM categories WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    set_flash('error', 'Category not found.');
    redirect('admin/categories/index.php');
}

$countStmt = db()->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
$countStmt->execute([$id]);
$productCount = (int) $countStmt->fetchColumn();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($productCount > 0) {
        $errors[] = 'Cannot delete this category because it has ' . $productCount . ' linked product(s). Move or delete those products first.';
    } else {
        try {
            $delete = db()->prepare('DELETE FROM categories WHERE id = ?');
            $delete->execute([$id]);
            set_flash('success', 'Category deleted successfully.');
            redirect('admin/categories/index.php');
        } catch (Throwable $e) {
            $errors[] = 'Could not delete category. Please try again.';
        }
    }
}

$pageTitle = 'Delete Category — Admin — ' . SITE_NAME;
require $root . '/includes/header.php';
?>

<section class="mx-auto max-w-xl px-4 py-10">
    <h1 class="font-display text-3xl font-semibold text-ink">Delete category</h1>

    <?php if ($errors !== []): ?>
        <div class="mt-6 space-y-1 rounded-md border border-red-600/30 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($productCount > 0): ?>
        <div class="mt-6 rounded-md border border-amber-600/30 bg-amber-50 px-4 py-3 text-sm text-amber-950" role="status">
            <p>
                <strong><?= e($category['name']) ?></strong> has
                <strong><?= e((string) $productCount) ?></strong> linked product(s).
                Deletion is blocked until those products are reassigned or removed.
            </p>
        </div>
        <div class="mt-6">
            <a href="<?= e(url('admin/categories/index.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40">Back to categories</a>
        </div>
    <?php else: ?>
        <p class="mt-4 text-ink/70">
            Are you sure you want to delete <strong class="text-ink"><?= e($category['name']) ?></strong>? This cannot be undone.
        </p>
        <form method="post" action="<?= e(url('admin/categories/delete.php?id=' . $id)) ?>" class="mt-8 flex flex-wrap gap-3">
            <input type="hidden" name="id" value="<?= e((string) $id) ?>">
            <button type="submit" class="rounded-md bg-red-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-800">Yes, delete</button>
            <a href="<?= e(url('admin/categories/index.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40">Cancel</a>
        </form>
    <?php endif; ?>
</section>

<?php require $root . '/includes/footer.php'; ?>
