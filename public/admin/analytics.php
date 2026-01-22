<?php

declare(strict_types=1);

require __DIR__ . '/../../config/bootstrap.php';

use App\Security\Auth;

$auth = new Auth($pdo);
$auth->requireLogin();

$title = 'Аналитика';

$today = new DateTimeImmutable('today');
$todayStr = $today->format('Y-m-d');

$roomTotals = $pdo->query('SELECT COUNT(*) AS types, COALESCE(SUM(quantity), 0) AS total FROM rooms')->fetch();
$roomTypes = (int) ($roomTotals['types'] ?? 0);
$roomInventory = (int) ($roomTotals['total'] ?? 0);

$bookingTotals = $pdo->query(
    'SELECT'
    . ' COUNT(*) AS total,'
    . " SUM(status = 'confirmed') AS confirmed,"
    . " SUM(status = 'reserved') AS reserved,"
    . " SUM(status = 'cancelled') AS cancelled,"
    . " COALESCE(SUM(CASE WHEN status != 'cancelled' THEN total ELSE 0 END), 0) AS revenue"
    . ' FROM bookings'
)->fetch();

$bookingsTotal = (int) ($bookingTotals['total'] ?? 0);
$bookingsConfirmed = (int) ($bookingTotals['confirmed'] ?? 0);
$bookingsReserved = (int) ($bookingTotals['reserved'] ?? 0);
$bookingsCancelled = (int) ($bookingTotals['cancelled'] ?? 0);
$revenueTotal = (float) ($bookingTotals['revenue'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM bookings'
    . ' WHERE status != :cancelled'
    . ' AND date_in <= :today_in AND date_out > :today_out'
);
$stmt->execute([
    'cancelled' => 'cancelled',
    'today_in' => $todayStr,
    'today_out' => $todayStr,
]);
$bookedToday = (int) $stmt->fetchColumn();
$occupancy = $roomInventory > 0 ? round(($bookedToday / $roomInventory) * 100, 1) : 0.0;

$stmt = $pdo->query(
    'SELECT COALESCE(AVG(DATEDIFF(date_out, date_in)), 0) AS avg_nights'
    . ' FROM bookings WHERE status != \'cancelled\''
);
$avgNights = (float) $stmt->fetchColumn();

$monthStart = (new DateTimeImmutable('first day of this month'))->format('Y-m-d');
$monthEnd = (new DateTimeImmutable('first day of next month'))->format('Y-m-d');
$stmt = $pdo->prepare(
    'SELECT COUNT(*) AS total, COALESCE(SUM(CASE WHEN status != :cancelled THEN total ELSE 0 END), 0) AS revenue'
    . ' FROM bookings WHERE date_in >= :start AND date_in < :end'
);
$stmt->execute([
    'cancelled' => 'cancelled',
    'start' => $monthStart,
    'end' => $monthEnd,
]);
$monthStats = $stmt->fetch();
$monthBookings = (int) ($monthStats['total'] ?? 0);
$monthRevenue = (float) ($monthStats['revenue'] ?? 0);

$monthlyRows = $pdo->query(
    'SELECT DATE_FORMAT(date_in, \'%Y-%m\') AS ym,'
    . ' COUNT(*) AS total,'
    . " COALESCE(SUM(CASE WHEN status != 'cancelled' THEN total ELSE 0 END), 0) AS revenue"
    . ' FROM bookings'
    . ' GROUP BY ym'
    . ' ORDER BY ym DESC'
    . ' LIMIT 6'
)->fetchAll();

$monthlyRowsChart = array_reverse($monthlyRows);
$monthlyMaxTotal = 0;
$monthlyMaxRevenue = 0.0;
foreach ($monthlyRowsChart as $row) {
    $monthlyMaxTotal = max($monthlyMaxTotal, (int) $row['total']);
    $monthlyMaxRevenue = max($monthlyMaxRevenue, (float) $row['revenue']);
}

$statusChart = [
    [
        'label' => 'Подтверждено',
        'value' => $bookingsConfirmed,
        'color' => 'var(--admin-success)',
    ],
    [
        'label' => 'Резерв',
        'value' => $bookingsReserved,
        'color' => '#f59e0b',
    ],
    [
        'label' => 'Отменено',
        'value' => $bookingsCancelled,
        'color' => 'var(--admin-danger)',
    ],
];

$popularRooms = $pdo->query(
    'SELECT rooms.number, categories.name AS category, floors.name AS floor_name,'
    . ' room_views.name AS view_name,'
    . ' COUNT(*) AS total,'
    . " COALESCE(SUM(CASE WHEN bookings.status != 'cancelled' THEN bookings.total ELSE 0 END), 0) AS revenue"
    . ' FROM bookings'
    . ' INNER JOIN rooms ON rooms.id = bookings.room_id'
    . ' INNER JOIN categories ON categories.id = rooms.category_id'
    . ' INNER JOIN floors ON floors.id = rooms.floor_id'
    . ' INNER JOIN room_views ON room_views.id = rooms.view_id'
    . ' GROUP BY rooms.id'
    . ' ORDER BY total DESC'
    . ' LIMIT 5'
)->fetchAll();

require __DIR__ . '/../../templates/admin/header.php';
?>
<section class="admin-card">
  <h2>Сводные показатели</h2>
  <div class="analytics-grid">
    <div class="stat-card">
      <p class="stat-label">Инвентарь</p>
      <p class="stat-value"><?= e((string) $roomInventory) ?> номеров</p>
      <p class="stat-hint">Типов: <?= e((string) $roomTypes) ?></p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Загрузка сегодня</p>
      <p class="stat-value"><?= e((string) $bookedToday) ?> / <?= e((string) $roomInventory) ?></p>
      <p class="stat-hint"><?= e((string) $occupancy) ?>%</p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Бронирования всего</p>
      <p class="stat-value"><?= e((string) $bookingsTotal) ?></p>
      <p class="stat-hint">Подтв.: <?= e((string) $bookingsConfirmed) ?></p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Бронирования в месяце</p>
      <p class="stat-value"><?= e((string) $monthBookings) ?></p>
      <p class="stat-hint">Выручка: <?= e(number_format($monthRevenue, 2, '.', ' ')) ?> ₽</p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Выручка всего</p>
      <p class="stat-value"><?= e(number_format($revenueTotal, 2, '.', ' ')) ?> ₽</p>
      <p class="stat-hint">Средняя длина: <?= e(number_format($avgNights, 1, '.', ' ')) ?> ночи</p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Статусы</p>
      <p class="stat-value">Резерв: <?= e((string) $bookingsReserved) ?></p>
      <p class="stat-hint">Отменено: <?= e((string) $bookingsCancelled) ?></p>
    </div>
  </div>
</section>

<section class="admin-card">
  <h2>Графические показатели</h2>
  <div class="chart-grid">
    <div class="chart-card">
      <h3>Загрузка сегодня</h3>
      <div class="progress">
        <span class="progress-bar" style="width: <?= e((string) $occupancy) ?>%"></span>
      </div>
      <p class="chart-note">Занято: <?= e((string) $bookedToday) ?> из <?= e((string) $roomInventory) ?> (<?= e((string) $occupancy) ?>%)</p>
    </div>
    <div class="chart-card">
      <h3>Статусы бронирований</h3>
      <?php foreach ($statusChart as $status): ?>
        <?php
          $percent = $bookingsTotal > 0 ? round(($status['value'] / $bookingsTotal) * 100, 1) : 0.0;
        ?>
        <div class="hbar">
          <div class="hbar-label">
            <span><?= e($status['label']) ?></span>
            <span><?= e((string) $status['value']) ?> (<?= e((string) $percent) ?>%)</span>
          </div>
          <div class="hbar-track">
            <span class="hbar-fill" style="width: <?= e((string) $percent) ?>%; background: <?= e($status['color']) ?>"></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="chart-card">
      <h3>Бронирования по месяцам</h3>
      <div class="bar-chart">
        <?php if ($monthlyRowsChart === []): ?>
          <p class="chart-note">Нет данных для построения графика.</p>
        <?php else: ?>
          <?php foreach ($monthlyRowsChart as $row): ?>
            <?php $height = $monthlyMaxTotal > 0 ? round(((int) $row['total'] / $monthlyMaxTotal) * 100) : 0; ?>
            <div class="bar-column">
              <div class="bar" style="height: <?= e((string) $height) ?>%"></div>
              <div class="bar-value"><?= e((string) $row['total']) ?></div>
              <div class="bar-label"><?= e((string) $row['ym']) ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="chart-card">
      <h3>Выручка по месяцам</h3>
      <div class="bar-chart bar-chart-secondary">
        <?php if ($monthlyRowsChart === []): ?>
          <p class="chart-note">Нет данных для построения графика.</p>
        <?php else: ?>
          <?php foreach ($monthlyRowsChart as $row): ?>
            <?php $height = $monthlyMaxRevenue > 0 ? round(((float) $row['revenue'] / $monthlyMaxRevenue) * 100) : 0; ?>
            <div class="bar-column">
              <div class="bar" style="height: <?= e((string) $height) ?>%"></div>
              <div class="bar-value"><?= e(number_format((float) $row['revenue'], 0, '.', ' ')) ?></div>
              <div class="bar-label"><?= e((string) $row['ym']) ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="admin-card">
  <h2>Бронирования по месяцам</h2>
  <table class="admin-table">
    <thead>
      <tr>
        <th>Месяц</th>
        <th>Бронирований</th>
        <th>Выручка (руб.)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($monthlyRows as $row): ?>
        <tr>
          <td><?= e((string) $row['ym']) ?></td>
          <td><?= e((string) $row['total']) ?></td>
          <td><?= e(number_format((float) $row['revenue'], 2, '.', ' ')) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if ($monthlyRows === []): ?>
        <tr>
          <td colspan="3">Данных пока нет.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>

<section class="admin-card">
  <h2>Популярные типы номеров</h2>
  <table class="admin-table">
    <thead>
      <tr>
        <th>Тип</th>
        <th>Этаж</th>
        <th>Вид</th>
        <th>Бронирований</th>
        <th>Выручка (руб.)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($popularRooms as $room): ?>
        <tr>
          <td><?= e(sprintf('Тип %s (%s)', $room['number'], $room['category'])) ?></td>
          <td><?= e((string) $room['floor_name']) ?></td>
          <td><?= e((string) $room['view_name']) ?></td>
          <td><?= e((string) $room['total']) ?></td>
          <td><?= e(number_format((float) $room['revenue'], 2, '.', ' ')) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if ($popularRooms === []): ?>
        <tr>
          <td colspan="5">Данных пока нет.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?php
require __DIR__ . '/../../templates/admin/footer.php';
