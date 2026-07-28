# Default Admin Account Setup Guide

This guide explains how to create the first **administrator** user for KINMEL E-Commerce.

**Do not store plain-text passwords in SQL files or the repository.**

---

## Prerequisites

1. Import `database/ecommerce.sql` so the `users` table exists.
2. Confirm PHP 8+ is available on your machine (`php -v`).
3. MySQL/MariaDB is running and `config/database.php` credentials are correct.

---

## Step 1 — Choose an admin password

Pick a strong password for local development (example only — use your own):

```text
YourSecureAdminPassword
```

---

## Step 2 — Generate a password hash with PHP

Run this in a terminal (replace the password string with yours):

```bash
php -r "echo password_hash('YourSecureAdminPassword', PASSWORD_DEFAULT), PHP_EOL;"
```

Example output (yours will be different every time):

```text
$2y$10$abcdefghijklmnopqrstuvEXAMPLE_HASH_PLACEHOLDER_xxxxxxxxxxx
```

Copy the full hash string.

---

## Step 3 — Insert the admin user

In phpMyAdmin (SQL tab) or the MySQL CLI, run:

```sql
INSERT INTO `users` (`name`, `email`, `password`, `role`)
VALUES (
  'Site Admin',
  'admin@kinmel.local',
  '$2y$10$REPLACE_THIS_WITH_YOUR_PASSWORD_HASH',
  'admin'
);
```

Replace:

| Placeholder | With |
|-------------|------|
| `Site Admin` | Display name you want |
| `admin@kinmel.local` | Admin email you will use to log in |
| `$2y$10$REPLACE_THIS_WITH_YOUR_PASSWORD_HASH` | Exact hash from Step 2 |

---

## Step 4 — Log in

1. Open: `http://localhost/kinmelEcommerce/admin/login.php`  
   (adjust host/path to match your `BASE_URL` in `config/config.php`)
2. Enter the **email** and **plain password** from Steps 1–3 (not the hash).
3. On success you should reach `admin/dashboard.php`.

---

## Security notes

- Never commit real password hashes that match production secrets.
- Never put the plain password inside the `INSERT` statement.
- Customer accounts should be created via `customer/register.php`, not by copying admin SQL.
- To reset an admin password later, generate a new hash (Step 2) and run:

```sql
UPDATE `users`
SET `password` = '$2y$10$REPLACE_THIS_WITH_YOUR_NEW_PASSWORD_HASH'
WHERE `email` = 'admin@kinmel.local'
  AND `role` = 'admin';
```

---

## Optional: one-time local hash helper

If you prefer a tiny script (run once, then delete):

```php
<?php
// save as tools/hash_password.php temporarily — do not deploy
echo password_hash($argv[1] ?? 'change-me', PASSWORD_DEFAULT), PHP_EOL;
```

```bash
php tools/hash_password.php "YourSecureAdminPassword"
```

Delete the script after use so passwords are not left in command history files unnecessarily.
