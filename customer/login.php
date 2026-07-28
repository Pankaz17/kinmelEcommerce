<?php
/**
 * KINMEL E-Commerce System — Customer login
 */

declare(strict_types=1);

require dirname(__DIR__) . '/config/config.php';
require dirname(__DIR__) . '/config/session.php';
require dirname(__DIR__) . '/config/database.php';
require dirname(__DIR__) . '/includes/functions.php';
require dirname(__DIR__) . '/includes/auth.php';

if (checkLogin()) {
    redirect($_SESSION['user_role'] === 'admin' ? 'admin/dashboard.php' : 'customer/profile.php');
}

$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = db()->prepare(
                'SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1'
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, (string) $user['password'])) {
                $errors[] = 'Invalid email or password.';
            } elseif ($user['role'] !== 'customer') {
                $errors[] = 'This portal is for customers only. Admins should use the admin login.';
            } else {
                loginUser((int) $user['id'], (string) $user['name'], (string) $user['role']);
                set_flash('success', 'Welcome back, ' . $user['name'] . '!');
                redirect('customer/profile.php');
            }
        } catch (Throwable $e) {
            $errors[] = 'Login failed. Please try again later.';
        }
    }
}

$pageTitle = 'Login — ' . SITE_NAME;
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="mx-auto max-w-lg px-4 py-12">
    <h1 class="font-display text-3xl font-semibold tracking-tight text-ink">Customer login</h1>
    <p class="mt-2 text-sm text-ink/65">Sign in to access your KINMEL account.</p>

    <?php if ($errors !== []): ?>
        <div class="mt-6 space-y-2 rounded-md border border-red-600/30 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('customer/login.php')) ?>" class="mt-8 space-y-5" novalidate>
        <div>
            <label for="email" class="block text-sm font-medium text-ink">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($email) ?>"
                required
                maxlength="150"
                autocomplete="email"
                class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 text-ink outline-none ring-moss/30 focus:ring-2"
            >
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-ink">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 text-ink outline-none ring-moss/30 focus:ring-2"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-md bg-moss px-4 py-2.5 text-sm font-semibold text-sand transition hover:bg-leaf"
        >
            Log in
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-ink/65">
        New here?
        <a href="<?= e(url('customer/register.php')) ?>" class="font-semibold text-moss hover:text-leaf">Create an account</a>
    </p>
    <p class="mt-2 text-center text-sm text-ink/50">
        Administrator?
        <a href="<?= e(url('admin/login.php')) ?>" class="font-medium text-ink/70 underline-offset-2 hover:underline">Admin login</a>
    </p>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
