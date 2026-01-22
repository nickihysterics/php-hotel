<?php

declare(strict_types=1);

require __DIR__ . '/../../config/bootstrap.php';

use App\Security\Auth;

$auth = new Auth($pdo);
$auth->requireLogin();

$statusLabels = [
    'reserved' => 'Резерв',
    'confirmed' => 'Подтверждено',
    'cancelled' => 'Отменено',
];

$yesNo = static fn (int $value): string => $value === 1 ? 'Да' : 'Нет';

$exports = [
    'rooms' => [
        'filename' => 'rooms',
        'headers' => [
            'ID',
            'Тип номера',
            'Категория',
            'Цена',
            'Количество',
            'Этаж',
            'Номер этажа',
            'Вид',
            'Спальных мест',
            'Раздельный санузел',
            'Тип санузла',
            'Фото',
        ],
        'rows' => static function (PDO $pdo) use ($yesNo): array {
            $sql = 'SELECT rooms.id, rooms.number, categories.name AS category, rooms.price, rooms.quantity,'
                . ' floors.name AS floor_name, floors.level AS floor_level,'
                . ' room_views.name AS view_name,'
                . ' rooms.bed_count, rooms.bathroom_separate, bathroom_types.name AS bathroom_type,'
                . ' rooms.photo'
                . ' FROM rooms'
                . ' INNER JOIN categories ON categories.id = rooms.category_id'
                . ' INNER JOIN floors ON floors.id = rooms.floor_id'
                . ' INNER JOIN room_views ON room_views.id = rooms.view_id'
                . ' INNER JOIN bathroom_types ON bathroom_types.id = rooms.bathroom_type_id'
                . ' ORDER BY rooms.id';
            $rows = [];
            foreach ($pdo->query($sql)->fetchAll() as $row) {
                $rows[] = [
                    $row['id'],
                    $row['number'],
                    $row['category'],
                    $row['price'],
                    $row['quantity'],
                    $row['floor_name'],
                    $row['floor_level'],
                    $row['view_name'],
                    $row['bed_count'],
                    $yesNo((int) $row['bathroom_separate']),
                    $row['bathroom_type'],
                    $row['photo'],
                ];
            }
            return $rows;
        },
    ],
    'bookings' => [
        'filename' => 'bookings',
        'headers' => [
            'ID',
            'Дата заезда',
            'Дата выезда',
            'Статус',
            'Сумма',
            'Клиент',
            'Сотрудник',
            'Тип номера',
            'Категория',
            'Этаж',
            'Вид',
            'Спальных мест',
            'Раздельный санузел',
            'Тип санузла',
        ],
        'rows' => static function (PDO $pdo) use ($statusLabels, $yesNo): array {
            $sql = 'SELECT bookings.id, bookings.date_in, bookings.date_out, bookings.status, bookings.total,'
                . ' CONCAT(clients.last_name, " ", clients.first_name) AS client_name,'
                . ' CONCAT(associates.last_name, " ", associates.first_name) AS associate_name,'
                . ' rooms.number AS room_number, categories.name AS category,'
                . ' floors.name AS floor_name, room_views.name AS view_name,'
                . ' rooms.bed_count, rooms.bathroom_separate, bathroom_types.name AS bathroom_type'
                . ' FROM bookings'
                . ' INNER JOIN clients ON clients.id = bookings.client_id'
                . ' INNER JOIN associates ON associates.id = bookings.associate_id'
                . ' INNER JOIN rooms ON rooms.id = bookings.room_id'
                . ' INNER JOIN categories ON categories.id = rooms.category_id'
                . ' INNER JOIN floors ON floors.id = rooms.floor_id'
                . ' INNER JOIN room_views ON room_views.id = rooms.view_id'
                . ' INNER JOIN bathroom_types ON bathroom_types.id = rooms.bathroom_type_id'
                . ' ORDER BY bookings.id';
            $rows = [];
            foreach ($pdo->query($sql)->fetchAll() as $row) {
                $rows[] = [
                    $row['id'],
                    $row['date_in'],
                    $row['date_out'],
                    $statusLabels[$row['status']] ?? $row['status'],
                    $row['total'],
                    $row['client_name'],
                    $row['associate_name'],
                    $row['room_number'],
                    $row['category'],
                    $row['floor_name'],
                    $row['view_name'],
                    $row['bed_count'],
                    $yesNo((int) $row['bathroom_separate']),
                    $row['bathroom_type'],
                ];
            }
            return $rows;
        },
    ],
    'clients' => [
        'filename' => 'clients',
        'headers' => [
            'ID',
            'Фамилия',
            'Имя',
            'Отчество',
            'Дата рождения',
            'Телефон',
        ],
        'rows' => static function (PDO $pdo): array {
            $rows = [];
            $stmt = $pdo->query('SELECT id, last_name, first_name, middle_name, birth_date, phone FROM clients ORDER BY id');
            foreach ($stmt->fetchAll() as $row) {
                $rows[] = [
                    $row['id'],
                    $row['last_name'],
                    $row['first_name'],
                    $row['middle_name'],
                    $row['birth_date'],
                    $row['phone'],
                ];
            }
            return $rows;
        },
    ],
    'associates' => [
        'filename' => 'associates',
        'headers' => [
            'ID',
            'Фамилия',
            'Имя',
            'Отчество',
            'Должность',
            'Телефон',
            'Дата рождения',
        ],
        'rows' => static function (PDO $pdo): array {
            $rows = [];
            $stmt = $pdo->query(
                'SELECT id, last_name, first_name, middle_name, position, phone, birth_date'
                . ' FROM associates ORDER BY id'
            );
            foreach ($stmt->fetchAll() as $row) {
                $rows[] = [
                    $row['id'],
                    $row['last_name'],
                    $row['first_name'],
                    $row['middle_name'],
                    $row['position'],
                    $row['phone'],
                    $row['birth_date'],
                ];
            }
            return $rows;
        },
    ],
    'floors' => [
        'filename' => 'floors',
        'headers' => [
            'ID',
            'Название',
            'Номер этажа',
            'Основной вид',
        ],
        'rows' => static function (PDO $pdo): array {
            $rows = [];
            $stmt = $pdo->query(
                'SELECT floors.id, floors.name, floors.level, room_views.name AS view_name'
                . ' FROM floors'
                . ' INNER JOIN room_views ON room_views.id = floors.view_id'
                . ' ORDER BY floors.level'
            );
            foreach ($stmt->fetchAll() as $row) {
                $rows[] = [
                    $row['id'],
                    $row['name'],
                    $row['level'],
                    $row['view_name'],
                ];
            }
            return $rows;
        },
    ],
];

$type = (string) ($_GET['type'] ?? '');
if (!isset($exports[$type])) {
    http_response_code(404);
    echo 'Неизвестный тип экспорта.';
    exit;
}

$export = $exports[$type];
$dateSuffix = (new DateTimeImmutable())->format('Y-m-d');
$filename = sprintf('%s-%s.csv', $export['filename'], $dateSuffix);

header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'wb');
fputcsv($output, $export['headers'], ';');
foreach ($export['rows']($pdo) as $row) {
    fputcsv($output, $row, ';');
}
fclose($output);
exit;
