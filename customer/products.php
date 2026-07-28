<?php
/**
 * KINMEL E-Commerce System — Customer product listing
 *
 * Supports:
 *   ?category=1
 *   ?search=shoe
 */

declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';
require dirname(__DIR__) . '/config/session.php';
require dirname(__DIR__) . '/config/database.php';
require dirname(__DIR__) . '/includes/functions.php';
require dirname(__DIR__) . '/includes/auth.php';

$categoryId = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
$search = trim((string) ($_GET['search'] ?? ''));

if ($categoryId === false || $categoryId === null || $categoryId < 1) {
    $categoryId = null;
}

if (mb_strlen($search) > 100) {
    $search = mb_substr($search, 0, 100);
}

$categories = db()->query(
    "SELECT id, name
     FROM categories
     WHERE status = 'active'
     ORDER BY name ASC"
)->fetchAll();

$sql = "SELECT p.id, p.name, p.price, p.stock, p.image, p.status,
               c.name AS category_name
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE p.status = 'active'";
$params = [];

if ($categoryId !== null) {
    $sql .= ' AND p.category_id = ?';
    $params[] = $categoryId;
}

if ($search !== '') {
    $sql .= ' AND p.name LIKE ?';
    $params[] = '%' . $search . '%';
}

$sql .= ' ORDER BY p.created_at DESC, p.id DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$selectedCategoryName = null;
if ($categoryId !== null) {
    foreach ($categories as $cat) {
        if ((int) $cat['id'] === $categoryId) {
            $selectedCategoryName = (string) $cat['name'];
            break;
        }
    }
}

$pageTitle = 'Products — ' . SITE_NAME;
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="max-w-2xl">
        <p class="text-sm font-medium uppercase tracking-wider text-ink/45">Storefront</p>
        <h1 class="mt-1 font-display text-3xl font-semibold tracking-tight text-ink">Products</h1>
        <p class="mt-2 text-ink/65">Browse active items from the KINMEL catalog.</p>
    </div>

    <form method="get" action="<?= e(url('customer/products.php')) ?>" class="mt-8 flex flex-col gap-3 border border-ink/10 bg-white/60 p-4 sm:flex-row sm:items-end">
        <div class="min-w-0 flex-1">
            <label for="search" class="block text-sm font-medium text-ink">Search</label>
            <input
                type="search"
                id="search"
                name="search"
                value="<?= e($search) ?>"
                placeholder="Search by product name"
                maxlength="100"
                class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 text-sm outline-none ring-moss/30 focus:ring-2"
            >
        </div>
        <div class="sm:w-56">
            <label for="category" class="block text-sm font-medium text-ink">Category</label>
            <select
                id="category"
                name="category"
                class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 text-sm outline-none ring-moss/30 focus:ring-2"
            >
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= e((string) $cat['id']) ?>" <?= $categoryId === (int) $cat['id'] ? 'selected' : '' ?>>
                        <?= e($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-md bg-moss px-4 py-2 text-sm font-semibold text-sand hover:bg-leaf">
                Apply
            </button>
            <a href="<?= e(url('customer/products.php')) ?>" class="rounded-md border border-ink/15 bg-white px-4 py-2 text-sm font-semibold text-ink hover:border-moss/40">
                Reset
            </a>
        </div>
    </form>

    <?php if ($search !== '' || $selectedCategoryName !== null): ?>
        <p class="mt-4 text-sm text-ink/60">
            Showing
            <strong class="text-ink"><?= e((string) count($products)) ?></strong>
            result(s)
            <?php if ($search !== ''): ?>
                for “<?= e($search) ?>”
            <?php endif; ?>
            <?php if ($selectedCategoryName !== null): ?>
                in <strong class="text-ink"><?= e($selectedCategoryName) ?></strong>
            <?php elseif ($categoryId !== null): ?>
                in the selected category
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <?php if ($products === []): ?>
        <div class="mt-10 border border-dashed border-ink/20 bg-white/40 px-6 py-12 text-center">
            <p class="font-medium text-ink/70">No products found.</p>
            <p class="mt-2 text-sm text-ink/50">Try another search term or category, or check back later.</p>
        </div>
    <?php else: ?>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($products as $product): ?>
                <?php require dirname(__DIR__) . '/includes/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
