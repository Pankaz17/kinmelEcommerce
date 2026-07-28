<?php
/**
 * KINMEL E-Commerce System — Customer registration
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

$name = '';
$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'Name must be 100 characters or fewer.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (mb_strlen($email) > 150) {
        $errors[] = 'Email must be 150 characters or fewer.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($confirm === '') {
        $errors[] = 'Please confirm your password.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Password and confirmation do not match.';
    }

    if ($errors === []) {
        try {
            $pdo = db();

            $check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $check->execute([$email]);

            if ($check->fetch()) {
                $errors[] = 'An account with this email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $insert = $pdo->prepare(
                    'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
                );
                $insert->execute([$name, $email, $hash, 'customer']);

                set_flash('success', 'Registration successful. Please log in.');
                redirect('customer/login.php');
            }
        } catch (Throwable $e) {
            $errors[] = 'Registration failed. Please try again later.';
        }
    }
}

$pageTitle = 'Register — ' . SITE_NAME;
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="mx-auto max-w-lg px-4 py-12">
    <h1 class="font-display text-3xl font-semibold tracking-tight text-ink">Create account</h1>
    <p class="mt-2 text-sm text-ink/65">Register as a customer to shop on KINMEL.</p>

    <?php if ($errors !== []): ?>
        <div class="mt-6 space-y-2 rounded-md border border-red-600/30 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('customer/register.php')) ?>" class="mt-8 space-y-5" novalidate>
        <div>
            <label for="name" class="block text-sm font-medium text-ink">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= e($name) ?>"
                required
                maxlength="100"
                autocomplete="name"
                class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 text-ink outline-none ring-moss/30 focus:ring-2"
            >
        </div>

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
                minlength="6"
                autocomplete="new-password"
                class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 text-ink outline-none ring-moss/30 focus:ring-2"
            >
            <p class="mt-1 text-xs text-ink/50">At least 6 characters.</p>
        </div>

        <div>
            <label for="confirm_password" class="block text-sm font-medium text-ink">Confirm password</label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                required
                minlength="6"
                autocomplete="new-password"
                class="mt-1 w-full rounded-md border border-ink/15 bg-white px-3 py-2 text-ink outline-none ring-moss/30 focus:ring-2"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-md bg-moss px-4 py-2.5 text-sm font-semibold text-sand transition hover:bg-leaf"
        >
            Register
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-ink/65">
        Already have an account?
        <a href="<?= e(url('customer/login.php')) ?>" class="font-semibold text-moss hover:text-leaf">Log in</a>
    </p>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
