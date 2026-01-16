<?php

declare(strict_types=1);

require __DIR__ . '/../../config/bootstrap.php';

use App\Security\Auth;

$auth = new Auth($pdo);
$auth->requireLogin();

$title = 'Пользователи';
$errors = [];
$formValues = [];
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;

if ($editId) {
    $stmt = $pdo->prepare('SELECT id, login FROM users WHERE id = :id');
    $stmt->execute(['id' => $editId]);
    $formValues = $stmt->fetch() ?: [];
}

if (is_post()) {
    if (!verify_csrf($_POST['_token'] ?? null)) {
        flash('error', 'Неверный токен безопасности.');
        header('Location: /admin/users.php');
        exit;
    }

    $action = (string) ($_POST['action'] ?? 'create');

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0 && $id !== (int) ($_SESSION['user_id'] ?? 0)) {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            flash('success', 'Пользователь удален.');
        } else {
            flash('error', 'Нельзя удалить текущего пользователя.');
        }
        header('Location: /admin/users.php');
        exit;
    }

    $login = trim((string) ($_POST['login'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($login === '') {
        $errors['login'] = 'Логин обязателен.';
    }

    if ($action === 'create' && $password === '') {
        $errors['password'] = 'Пароль обязателен.';
    }

    if ($errors === []) {
        $params = ['login' => $login];
        if ($action === 'update') {
            $params['id'] = (int) ($_POST['id'] ?? 0);
        }

        $query = $action === 'update'
            ? 'SELECT id FROM users WHERE login = :login AND id != :id'
            : 'SELECT id FROM users WHERE login = :login';

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors['login'] = 'Такой логин уже существует.';
        }
    }

    $formValues = ['login' => $login];

    if ($errors === []) {
        if ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $updates = ['login' => $login];
            $sql = 'UPDATE users SET login = :login';

            if ($password !== '') {
                $updates['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ', password_hash = :password_hash';
            }

            $sql .= ' WHERE id = :id';
            $updates['id'] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($updates);
            flash('success', 'Пользователь обновлен.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (login, password_hash, created_at) VALUES (:login, :password_hash, NOW())');
            $stmt->execute([
                'login' => $login,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            flash('success', 'Пользователь добавлен.');
        }

        header('Location: /admin/users.php');
        exit;
    }
}

$users = $pdo->query('SELECT id, login, created_at FROM users ORDER BY id')->fetchAll();

require __DIR__ . '/../../templates/admin/header.php';
?>
<section class="admin-card">
  <h2>Список пользователей</h2>
  <table class="admin-table">
    <thead>
      <tr>
        <th>Код</th>
        <th>Логин</th>
        <th>Создан</th>
        <th>Действия</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?= e((string) $user['id']) ?></td>
          <td><?= e($user['login']) ?></td>
          <td><?= e((string) ($user['created_at'] ?? '')) ?></td>
          <td class="table-actions">
            <a class="button button-link" href="?edit=<?= e((string) $user['id']) ?>">Изменить</a>
            <form class="inline-form" method="post" action="">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= e((string) $user['id']) ?>">
              <button class="button button-secondary" type="submit">Удалить</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<section class="admin-card">
  <h2><?= $editId ? 'Редактировать пользователя' : 'Добавить пользователя' ?></h2>
  <form class="admin-form" method="post" action="">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
    <?php if ($editId): ?>
      <input type="hidden" name="id" value="<?= e((string) $editId) ?>">
    <?php endif; ?>
    <div class="form-grid">
      <div class="form-field">
        <label for="login">Логин</label>
        <input id="login" name="login" type="text" value="<?= e((string) ($formValues['login'] ?? '')) ?>" required>
        <?php if (isset($errors['login'])): ?>
          <small><?= e($errors['login']) ?></small>
        <?php endif; ?>
      </div>
      <div class="form-field">
        <label for="password"><?= $editId ? 'Новый пароль (необязательно)' : 'Пароль' ?></label>
        <input id="password" name="password" type="password" <?= $editId ? '' : 'required' ?>>
        <?php if (isset($errors['password'])): ?>
          <small><?= e($errors['password']) ?></small>
        <?php endif; ?>
      </div>
    </div>
    <div class="form-actions">
      <button class="button" type="submit"><?= $editId ? 'Сохранить' : 'Добавить' ?></button>
      <?php if ($editId): ?>
        <a class="button button-secondary" href="/admin/users.php">Отменить</a>
      <?php endif; ?>
    </div>
  </form>
</section>
<?php
require __DIR__ . '/../../templates/admin/footer.php';
