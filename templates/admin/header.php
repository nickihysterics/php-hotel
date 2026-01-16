<?php
/** @var string $title Заголовок страницы. */
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · Админ</title>
    <link rel="stylesheet" href="/css/admin.css">
  </head>
  <body>
    <header class="admin-header">
      <h1 class="admin-title">Администрация</h1>
      <nav>
        <ul class="topmenu">
          <li><a href="/admin/index.php">Главная</a></li>
          <li><a href="#" class="down">Справочники</a>
            <ul class="submenu">
              <li><a href="/admin/categories.php">Категории</a></li>
              <li><a href="/admin/rooms.php">Номера</a></li>
              <li><a href="/admin/associates.php">Сотрудники</a></li>
              <li><a href="/admin/clients.php">Клиенты</a></li>
              <li><a href="/admin/bookings.php">Бронирования</a></li>
            </ul>
          </li>
          <li><a href="/admin/users.php">Пользователи</a></li>
          <li><a href="/admin/logout.php">Выход</a></li>
        </ul>
      </nav>
      <p class="admin-user">Вы вошли как: <?= e($_SESSION['user_login'] ?? '') ?></p>
    </header>

    <main class="admin-main">
      <?php if ($message = flash('success')): ?>
        <div class="flash flash-success"><?= e($message) ?></div>
      <?php endif; ?>
      <?php if ($message = flash('error')): ?>
        <div class="flash flash-error"><?= e($message) ?></div>
      <?php endif; ?>
