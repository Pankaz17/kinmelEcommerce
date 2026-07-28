<?php
/**
 * KINMEL E-Commerce System — Admin: create category
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
require $root . '/config/config.php';
require $root . '/config/session.php';
require $root . '/config/database.php';
require $root . '/includes/functions.php';
require $root . '/includes/auth.php';

requireAdmin();

$name = '';
$description = '';
$status = 'active';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $status = (string) ($_POST['status'] ?? 'active');

    if ($name === '') {
        $errors[] = 'Category name is required.';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'Category name must be 100 characters or fewer.';
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = 'Invalid status selected.';
    }

    if ($errors === []) {
        try {
            $check = db()->prepare('SELECT id FROM categories WHERE name = ? LIMIT 1');
            $check->execute([$name]);
            if ($check->fetch()) {
                $errors[] = 'A category with this name already exists.';
            } else {
                $stmt = db()->prepare(
                    'INSERT INTO categories (name, description, status) VALUES (?, ?, ?)'
                );
                $stmt->execute([
                    $name,
                    $description !== '' ? $description : null,
                    $status,
                ]);
                set_flash('success', 'Category created successfully.');
                redirect('admin/categories/index.php');
            }
        } catch (Throwable $e) {
            $errors[] = 'Could not create category. Please try again.';
        }
    }
}

$pageTitle = 'Add Category — Admin — ' . SITE_NAME;
require $root . '/includes/header.php';
?>

<section class="mx-auto max-w-xl px-4 py-10">
    <h1 class="font-display text-3xl font-semibold text-ink">Add category</h1>
    <p class="mt-2 text-sm text-ink/65">Create a new product category.</p>

    <?php if ($errors !== []): ?>
        <div class="mt-6 space-y-1 rounded-md border border-red-600/30 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('admin/categories/create.php')) ?>" class="mt-8 space-y-5">
        <div>
            <label for="name" class="block text-sm font-medium text-ink">Name <span class="text-red-600">*</span></label>
            <input type="text" id="name" name="name" value="<?= e($name) ?>" required maxlength="100"
                   class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-ink">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2"><?= e($description) ?></textarea>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-ink">Status</label>
            <select id="status" name="status" class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="rounded-md bg-moss px-4 py-2.5 text-sm font-semibold text-sand hover:bg-leaf">Save category</button>
            <a href="<?= e(url('admin/categories/index.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40">Cancel</a>
        </div>
    </form>
</section>

<?php require $root . '/includes/footer.php'; ?>
