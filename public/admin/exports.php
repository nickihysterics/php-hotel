<?php

declare(strict_types=1);

require __DIR__ . '/../../config/bootstrap.php';

use App\Security\Auth;

$auth = new Auth($pdo);
$auth->requireLogin();

$title = 'Экспорт';

$exports = [
    [
        'type' => 'bookings',
        'title' => 'Бронирования',
        'description' => 'Все бронирования с привязкой к клиентам, сотрудникам и типам номеров.',
    ],
    [
        'type' => 'rooms',
        'title' => 'Типы номеров',
        'description' => 'Номерной фонд с этажами, видами и параметрами санузла.',
    ],
    [
        'type' => 'clients',
        'title' => 'Клиенты',
        'description' => 'Справочник клиентов.',
    ],
    [
        'type' => 'associates',
        'title' => 'Сотрудники',
        'description' => 'Справочник сотрудников.',
    ],
    [
        'type' => 'floors',
        'title' => 'Этажи',
        'description' => 'Справочник этажей и видов.',
    ],
];

require __DIR__ . '/../../templates/admin/header.php';
?>
<section class="admin-card">
  <h2>Экспорт в Excel (CSV)</h2>
  <p>Файлы выгружаются в формате CSV с кодировкой UTF-8 и открываются в Excel.</p>
  <div class="analytics-grid">
    <?php foreach ($exports as $export): ?>
      <div class="stat-card">
        <p class="stat-label"><?= e($export['title']) ?></p>
        <p class="stat-hint"><?= e($export['description']) ?></p>
        <a class="button" href="/admin/export.php?type=<?= e($export['type']) ?>">Скачать</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php
require __DIR__ . '/../../templates/admin/footer.php';
