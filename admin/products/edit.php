<?php
/**
 * KINMEL E-Commerce System — Admin: edit product
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

$stmt = db()->prepare(
    'SELECT id, category_id, name, description, price, stock, image, status
     FROM products WHERE id = ? LIMIT 1'
);
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('error', 'Product not found.');
    redirect('admin/products/index.php');
}

$categories = db()->query(
    'SELECT id, name, status FROM categories ORDER BY name ASC'
)->fetchAll();

$name = (string) $product['name'];
$description = (string) ($product['description'] ?? '');
$categoryId = (int) $product['category_id'];
$price = (string) $product['price'];
$stock = (string) $product['stock'];
$status = (string) $product['status'];
$currentImage = $product['image'] ?? null;
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

    $newImage = null;
    $uploaded = false;
    $hasNewFile = isset($_FILES['image'])
        && (int) ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

    if ($hasNewFile) {
        $upload = upload_product_image($_FILES['image']);
        if (!$upload['ok']) {
            $errors[] = $upload['error'];
        } else {
            $newImage = $upload['filename'];
            $uploaded = true;
        }
    }

    if ($errors === []) {
        $imageToSave = $newImage ?? $currentImage;

        try {
            $update = db()->prepare(
                'UPDATE products
                 SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, image = ?, status = ?
                 WHERE id = ?'
            );
            $update->execute([
                $categoryId,
                $name,
                $description !== '' ? $description : null,
                number_format((float) $price, 2, '.', ''),
                (int) $stock,
                $imageToSave,
                $status,
                $id,
            ]);

            if ($uploaded && $newImage !== null && $currentImage && $currentImage !== $newImage) {
                delete_product_image_file((string) $currentImage);
            }

            set_flash('success', 'Product updated successfully.');
            redirect('admin/products/index.php');
        } catch (Throwable $e) {
            if ($uploaded && $newImage !== null) {
                delete_product_image_file($newImage);
            }
            $errors[] = 'Could not update product. Please try again.';
        }
    } elseif ($uploaded && $newImage !== null) {
        delete_product_image_file($newImage);
    }
}

$previewUrl = product_image_url($currentImage);

$pageTitle = 'Edit Product — Admin — ' . SITE_NAME;
require $root . '/includes/header.php';
?>

<section class="mx-auto max-w-2xl px-4 py-10">
    <h1 class="font-display text-3xl font-semibold text-ink">Edit product</h1>
    <p class="mt-2 text-sm text-ink/65">Update product details and optional image.</p>

    <?php if ($errors !== []): ?>
        <div class="mt-6 space-y-1 rounded-md border border-red-600/30 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('admin/products/edit.php?id=' . $id)) ?>" enctype="multipart/form-data" class="mt-8 space-y-5">
        <input type="hidden" name="id" value="<?= e((string) $id) ?>">

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
                        <?= e($cat['name']) ?><?= $cat['status'] === 'inactive' ? ' (inactive)' : '' ?>
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
            <p class="block text-sm font-medium text-ink">Current image</p>
            <?php if ($previewUrl !== ''): ?>
                <img src="<?= e($previewUrl) ?>" alt="" class="mt-2 h-28 w-28 object-cover border border-ink/10" width="112" height="112">
            <?php else: ?>
                <p class="mt-2 text-sm text-ink/50">No image on file.</p>
            <?php endif; ?>
            <label for="image" class="mt-4 block text-sm font-medium text-ink">Replace image (optional)</label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif"
                   class="mt-1 block w-full text-sm text-ink/70 file:mr-3 file:rounded-md file:border-0 file:bg-moss file:px-3 file:py-2 file:text-sm file:font-semibold file:text-sand hover:file:bg-leaf">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-ink">Status</label>
            <select id="status" name="status" class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 outline-none ring-moss/30 focus:ring-2">
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="rounded-md bg-moss px-4 py-2.5 text-sm font-semibold text-sand hover:bg-leaf">Update product</button>
            <a href="<?= e(url('admin/products/index.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2.5 text-sm font-semibold text-ink hover:border-moss/40">Cancel</a>
        </div>
    </form>
</section>

<?php require $root . '/includes/footer.php'; ?>
