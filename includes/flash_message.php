<?php
/**
 * KINMEL E-Commerce System — Flash message display partial
 */

declare(strict_types=1);

$flash = get_flash();

if ($flash === null) {
    return;
}

$type = $flash['type'] ?? 'info';
$message = $flash['message'] ?? '';

$styles = match ($type) {
    'success' => 'border-emerald-600/30 bg-emerald-50 text-emerald-900',
    'error'   => 'border-red-600/30 bg-red-50 text-red-900',
    'warning' => 'border-amber-600/30 bg-amber-50 text-amber-950',
    default   => 'border-slate-400/40 bg-slate-50 text-slate-800',
};
?>
<div class="mx-auto max-w-6xl px-4 pt-4" role="status" aria-live="polite">
    <div class="rounded-md border px-4 py-3 text-sm <?= e($styles) ?>">
        <?= e($message) ?>
    </div>
</div>
