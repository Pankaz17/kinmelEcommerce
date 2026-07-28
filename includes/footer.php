<?php
/**
 * KINMEL E-Commerce System — Shared page footer
 */

declare(strict_types=1);
?>
</main>

<footer class="mt-16 border-t border-ink/10 bg-ink text-sand">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="font-display text-2xl font-semibold tracking-tight">KINMEL</p>
            <p class="mt-2 max-w-sm text-sm text-sand/70">
                <?= e(SITE_TAGLINE) ?> Academic e-commerce demonstration project.
            </p>
        </div>
        <div class="text-sm text-sand/60">
            <p>&copy; <?= e(date('Y')) ?> <?= e(SITE_NAME) ?></p>
            <p class="mt-1">Built with PHP 8+, MySQL, Tailwind CSS</p>
        </div>
    </div>
</footer>

<script src="<?= e(url('assets/js/main.js')) ?>"></script>
</body>
</html>
