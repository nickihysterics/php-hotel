<?php
/** @var string $title Заголовок страницы. */
$adminCssVersion = @filemtime(__DIR__ . '/../../public/css/admin.css') ?: time();
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · Админ</title>
    <link rel="stylesheet" href="/css/admin.css?v=<?= e((string) $adminCssVersion) ?>">
  </head>
  <body>
    <header class="admin-header">
      <h1 class="admin-title">Администрация</h1>
      <nav>
        <ul class="topmenu">
          <li><a href="/admin/index.php">Главная</a></li>
          <li><a href="/admin/bookings.php">Бронирования</a></li>
          <li><a href="#" class="down">Справочники</a>
            <ul class="submenu">
              <li><a href="/admin/categories.php">Категории</a></li>
              <li><a href="/admin/rooms.php">Типы номеров</a></li>
              <li><a href="/admin/floors.php">Этажи</a></li>
              <li><a href="/admin/room_views.php">Виды из номера</a></li>
              <li><a href="/admin/bathroom_types.php">Типы санузлов</a></li>
              <li><a href="/admin/clients.php">Клиенты</a></li>
              <li><a href="/admin/associates.php">Сотрудники</a></li>
            </ul>
          </li>
          <li><a href="/admin/analytics.php">Аналитика</a></li>
          <li><a href="/admin/exports.php">Экспорт</a></li>
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
