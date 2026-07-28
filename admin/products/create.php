<?php
/**
 * KINMEL E-Commerce System — Admin: create product
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
    'SELECT id, name FROM categories WHERE status = \'active\' ORDER BY name ASC'
)->fetchAll();

$name = '';
$description = '';
$categoryId = 0;
$price = '';
$stock = '0';
$status = 'active';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $price = trim((string) ($_POST['price'] ?? ''));
    $stock = trim((string) ($_POST['stock'] ?? ''));
    $status = (string) ($_POST['status'] ?? 'active');

    if ($name === '') {
        $errors[] = 'Product name is required.';
    } elseif (mb_strlen($name) > 150) {
        $errors[] = 'Product name must be 150 characters or fewer.';
    }

    if ($categoryId <= 0) {
        $errors[] = 'Please select a category.';
    } else {
        $catCheck = db()->prepare('SELECT id FROM categories WHERE id = ? LIMIT 1');
        $catCheck->execute([$categoryId]);
        if (!$catCheck->fetch()) {
            $errors[] = 'Selected category does not exist.';
        }
    }

    if ($price === '' || !is_numeric($price)) {
        $errors[] = 'Enter a valid price.';
    } elseif ((float) $price < 0) {
        $errors[] = 'Price cannot be negative.';
    }

    if ($stock === '' || !ctype_digit($stock)) {
        $errors[] = 'Stock must be a whole number (0 or greater).';
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = 'Invalid status selected.';
    }

    $imageName = null;
    $uploaded = false;

    if (!isset($_FILES['image']) || (int) ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Product image is required.';
    } else {
        $upload = upload_product_image($_FILES['image']);
        if (!$upload['ok']) {
            $errors[] = $upload['error'];
        } else {
            $imageName = $upload['filename'];
            $uploaded = true;
        }
    }

    if ($errors === [] && $imageName !== null) {
        try {
            $stmt = db()->prepare(
                'INSERT INTO products (category_id, name, description, price, stock, image, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $categoryId,
                $name,
                $description !== '' ? $description : null,
                number_format((float) $price, 2, '.', ''),
                (int) $stock,
                $imageName,
                $status,
            ]);
            set_flash('success', 'Product created successfully.');
            redirect('admin/products/index.php');
        } catch (Throwable $e) {
            if ($uploaded) {
                delete_product_image_file($imageName);
            }
            $errors[] = 'Could not create product. Please try again.';
        }
    } elseif ($uploaded && $imageName !== null) {
        delete_product_image_file($imageName);
    }
}

$pageTitle = 'Add Product — Admin — ' . SITE_NAME;
require $root . '/includes/header.php';
?>

<section class="mx-auto max-w-2xl px-4 py-10">
    <h1 class="font-display text-3xl font-semibold text-ink">Add product</h1>
    <p class="mt-2 text-sm text-ink/65">Create a catalog product with image and pricing.</p>

    <?php if ($categories === []): ?>
        <div class="mt-6 rounded-md border border-amber-600/30 bg-amber-50 px-4 py-3 text-sm text-amber-950">
            No active categories found.
            <a href="<?= e(url('admin/categories/create.php')) ?>" class="font-semibold underline">Create a category</a> first.
        </div>
    <?php endif; ?>

    <?php if ($errors !== []): ?>
        <div class="mt-6 space-y-1 rounded-md border border-red-600/30 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('admin/products/create.php')) ?>" enctype="multipart/form-data" class="mt-8 space-y-5">
        <div>
            <label for="name" class="block text-sm font-medium text-ink">Name <span class="text-red-600">*</span></label>
            <input type="text" id="name" name="name" value="<?= e($name) ?>" required maxlength="150"
                   class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-ink">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2"><?= e($description) ?></textarea>
        </div>
        <div>
            <label for="category_id" class="block text-sm font-medium text-ink">Category <span class="text-red-600">*</span></label>
            <select id="category_id" name="category_id" required
                    class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
                <option value="">Select category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= e((string) $cat['id']) ?>" <?= $categoryId === (int) $cat['id'] ? 'selected' : '' ?>>
                        <?= e($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="price" class="block text-sm font-medium text-ink">Price (<?= e(CURRENCY_CODE) ?>) <span class="text-red-600">*</span></label>
                <input type="number" id="price" name="price" value="<?= e($price) ?>" required min="0" step="0.01"
                       class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
            </div>
            <div>
                <label for="stock" class="block text-sm font-medium text-ink">Stock <span class="text-red-600">*</span></label>
                <input type="number" id="stock" name="stock" value="<?= e($stock) ?>" required min="0" step="1"
                       class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
            </div>
        </div>
        <div>
            <label for="image" class="block text-sm font-medium text-ink">Image <span class="text-red-600">*</span></label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" required
                   class="mt-1 block w-full text-sm text-ink/70 file:mr-3 file:rounded-md file:border-0 file:bg-moss file:px-3 file:py-2 file:text-sm file:font-semibold file:text-sand hover:file:bg-leaf">
            <p class="mt-1 text-xs text-ink/50">JPG, PNG, WEBP, or GIF · max 2 MB</p>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-ink">Status</label>
            <select id="status" name="status" class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="rounded-md bg-moss px-4 py-2.5 text-sm font-semibold text-sand hover:bg-leaf" <?= $categories === [] ? 'disabled' : '' ?>>Save product</button>
            <a href="<?= e(url('admin/products/index.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40">Cancel</a>
        </div>
    </form>
</section>

<?php require $root . '/includes/footer.php'; ?>
