<?php

declare(strict_types=1);

require __DIR__ . '/../../config/bootstrap.php';

use App\Config\Env;
use App\Security\Auth;

$auth = new Auth($pdo);

if ($auth->check()) {
    header('Location: /admin/index.php');
    exit;
}

$userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($userCount === 0) {
    $seedLogin = Env::get('ADMIN_USER', 'admin');
    $seedPassword = Env::get('ADMIN_PASS', 'admin123');

    $stmt = $pdo->prepare('INSERT INTO users (login, password_hash, created_at) VALUES (:login, :password_hash, NOW())');
    $stmt->execute([
        'login' => $seedLogin,
        'password_hash' => password_hash((string) $seedPassword, PASSWORD_DEFAULT),
    ]);
}

$login = '';

if (is_post()) {
    if (!verify_csrf($_POST['_token'] ?? null)) {
        flash('error', 'Неверный токен безопасности.');
        header('Location: /admin/login.php');
        exit;
    }

    $login = trim((string) ($_POST['login'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($login === '' || $password === '') {
        flash('error', 'Введите логин и пароль.');
    } elseif ($auth->attempt($login, $password)) {
        header('Location: /admin/index.php');
        exit;
    } else {
        flash('error', 'Неверный логин или пароль.');
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход · Админ</title>
    <link rel="stylesheet" href="/css/admin.css">
  </head>
  <body>
    <main class="admin-main">
      <?php if ($message = flash('error')): ?>
        <div class="flash flash-error"><?= e($message) ?></div>
      <?php endif; ?>

      <section class="admin-card">
        <h2>Вход в административную панель</h2>
        <form class="admin-form" method="post" action="/admin/login.php">
          <?= csrf_field() ?>
          <div class="form-grid">
            <div class="form-field">
              <label for="login">Логин</label>
              <input id="login" name="login" type="text" value="<?= e($login) ?>" required>
            </div>
            <div class="form-field">
              <label for="password">Пароль</label>
              <input id="password" name="password" type="password" required>
            </div>
          </div>
          <div class="form-actions">
            <button class="button" type="submit">Войти</button>
            <a class="button button-secondary" href="/">На сайт</a>
          </div>
        </form>
      </section>
    </main>
  </body>
</html>
