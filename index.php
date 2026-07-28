<?php
/**
 * KINMEL E-Commerce System — Homepage (foundation shell)
 *
 * Phase 1: layout + placeholders only. No catalog or cart logic.
 */

declare(strict_types=1);

require __DIR__ . '/config/config.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';
// Database bootstrap is available for later phases:
// require __DIR__ . '/config/database.php';

$pageTitle = SITE_NAME . ' — Home';

require __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="relative overflow-hidden border-b border-ink/10">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(47,107,79,0.18),_transparent_55%),linear-gradient(160deg,#f3eee6_0%,#e7dfd2_45%,#d5e4db_100%)]"></div>
    <div class="relative mx-auto grid max-w-6xl gap-10 px-4 py-16 sm:py-24 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:py-28">
        <div>
            <p class="font-display text-5xl font-bold leading-[1.05] tracking-tight text-moss sm:text-6xl lg:text-7xl">
                KINMEL
            </p>
            <h1 class="mt-5 max-w-xl text-2xl font-semibold leading-snug text-ink sm:text-3xl">
                Everyday essentials, curated for simple online shopping.
            </h1>
            <p class="mt-4 max-w-lg text-base leading-relaxed text-ink/70">
                <?= e(SITE_TAGLINE) ?> Browse categories and products once the catalog modules are connected.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="#products" class="inline-flex items-center rounded-md bg-moss px-5 py-2.5 text-sm font-semibold text-sand transition hover:bg-leaf">
                    Explore products
                </a>
                <a href="#categories" class="inline-flex items-center rounded-md border border-ink/15 bg-white/50 px-5 py-2.5 text-sm font-semibold text-ink transition hover:border-moss/40 hover:bg-white">
                    View categories
                </a>
            </div>
        </div>
        <div class="relative min-h-[220px] overflow-hidden rounded-none sm:min-h-[280px]">
            <div class="absolute inset-0 bg-[linear-gradient(135deg,#1f4d3a_0%,#2f6b4f_40%,#c45c26_100%)] opacity-90"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.08\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            <div class="relative flex h-full min-h-[220px] items-end p-6 sm:min-h-[280px] sm:p-8">
                <p class="max-w-xs font-display text-2xl font-semibold leading-tight text-sand sm:text-3xl">
                    Foundation ready.<br>
                    <span class="text-sand/75">Catalog coming next.</span>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Categories placeholder -->
<section id="categories" class="mx-auto max-w-6xl px-4 py-16">
    <div class="max-w-2xl">
        <h2 class="font-display text-3xl font-semibold tracking-tight text-ink">Categories</h2>
        <p class="mt-2 text-ink/65">
            Category listings will appear here after the category module is implemented.
        </p>
    </div>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="border border-dashed border-ink/20 bg-white/40 px-5 py-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-ink/40">Category placeholder</p>
                <p class="mt-2 text-ink/55">Slot <?= $i ?></p>
            </div>
        <?php endfor; ?>
    </div>
</section>

<!-- Products placeholder -->
<section id="products" class="border-t border-ink/10 bg-white/35">
    <div class="mx-auto max-w-6xl px-4 py-16">
        <div class="max-w-2xl">
            <h2 class="font-display text-3xl font-semibold tracking-tight text-ink">Featured products</h2>
            <p class="mt-2 text-ink/65">
                Product cards will load from the database in a later phase. These are layout placeholders only.
            </p>
        </div>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="border border-dashed border-ink/20 bg-sand/80 p-4">
                    <div class="flex aspect-[4/3] items-center justify-center bg-moss/10 text-sm text-moss/60">
                        Image placeholder
                    </div>
                    <p class="mt-4 text-sm font-semibold text-ink/40">Product placeholder</p>
                    <p class="mt-1 text-ink/70">Item <?= $i ?></p>
                    <p class="mt-2 text-sm text-ink/45"><?= e(CURRENCY_SYMBOL) ?> —.—</p>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
