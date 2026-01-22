<?php

declare(strict_types=1);

use App\Repository\CrudRepository;

$loadOptions = static function (PDO $pdo, string $table, string $labelExpression): array {
    $stmt = $pdo->query(sprintf('SELECT id, %s AS label FROM %s ORDER BY label', $labelExpression, $table));
    $options = [];
    foreach ($stmt->fetchAll() as $row) {
        $options[$row['id']] = $row['label'];
    }
    return $options;
};

$loadRoomRows = static function (PDO $pdo): array {
    $sql = 'SELECT rooms.id, rooms.number, rooms.price, rooms.quantity,'
        . ' categories.name AS category,'
        . ' floors.level AS floor_level, floors.name AS floor_name,'
        . ' room_views.name AS view_name'
        . ' FROM rooms'
        . ' INNER JOIN categories ON categories.id = rooms.category_id'
        . ' INNER JOIN floors ON floors.id = rooms.floor_id'
        . ' INNER JOIN room_views ON room_views.id = rooms.view_id'
        . ' ORDER BY floors.level, rooms.number';
    return $pdo->query($sql)->fetchAll();
};

$loadRoomListOptions = static function (PDO $pdo) use ($loadRoomRows): array {
    $options = [];
    foreach ($loadRoomRows($pdo) as $room) {
        $options[$room['id']] = sprintf(
            'Тип %s - %s - %s',
            $room['number'],
            $room['category'],
            $room['floor_name']
        );
    }
    return $options;
};

$loadAvailableRoomRows = static function (PDO $pdo, string $dateIn, string $dateOut, ?int $excludeBookingId = null): array {
    $sql = 'SELECT rooms.id, rooms.number, rooms.price, rooms.quantity, categories.name AS category,'
        . ' floors.level AS floor_level, floors.name AS floor_name, room_views.name AS view_name,'
        . ' COALESCE(booking_usage.booked, 0) AS booked'
        . ' FROM rooms'
        . ' INNER JOIN categories ON categories.id = rooms.category_id'
        . ' INNER JOIN floors ON floors.id = rooms.floor_id'
        . ' INNER JOIN room_views ON room_views.id = rooms.view_id'
        . ' LEFT JOIN ('
        . ' SELECT bookings.room_id, COUNT(*) AS booked'
        . ' FROM bookings'
        . ' WHERE bookings.status != :cancelled'
        . ' AND bookings.id != :current_id'
        . ' AND NOT (bookings.date_out <= :date_in OR bookings.date_in >= :date_out)'
        . ' GROUP BY bookings.room_id'
        . ' ) AS booking_usage ON rooms.id = booking_usage.room_id'
        . ' ORDER BY rooms.number';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'cancelled' => 'cancelled',
        'current_id' => $excludeBookingId ?? 0,
        'date_in' => $dateIn,
        'date_out' => $dateOut,
    ]);
    $available = [];
    foreach ($stmt->fetchAll() as $row) {
        $free = (int) $row['quantity'] - (int) $row['booked'];
        if ($free > 0) {
            $row['available'] = $free;
            $available[] = $row;
        }
    }
    return $available;
};

$statusOptions = [
    'reserved' => 'Резерв',
    'confirmed' => 'Подтверждено',
    'cancelled' => 'Отменено',
];

$loadRoomOptions = static function (PDO $pdo, array $formValues = [], ?int $editId = null) use ($loadRoomRows, $loadAvailableRoomRows): array {
    $dateIn = $formValues['date_in'] ?? null;
    $dateOut = $formValues['date_out'] ?? null;
    $status = $formValues['status'] ?? 'reserved';

    if (is_string($dateIn) && $dateIn !== '' && is_string($dateOut) && $dateOut !== '' && $status !== 'cancelled') {
        $rooms = $loadAvailableRoomRows($pdo, $dateIn, $dateOut, $editId);
    } else {
        $rooms = $loadRoomRows($pdo);
    }

    $options = [];
    foreach ($rooms as $room) {
        $price = number_format((float) $room['price'], 2, '.', ' ');
        $suffix = isset($room['available'])
            ? sprintf('Свободно: %d', (int) $room['available'])
            : sprintf('Всего: %d', (int) $room['quantity']);
        $options[$room['id']] = sprintf(
            'Тип %s - %s - %s - вид: %s - %s руб./сутки - %s',
            $room['number'],
            $room['category'],
            $room['floor_name'],
            $room['view_name'],
            $price,
            $suffix
        );
    }

    return $options;
};

$validateBooking = static function (PDO $pdo, array $data, ?int $recordId, string $action) use ($statusOptions, $loadAvailableRoomRows): array {
    if ($action === 'delete') {
        return [];
    }

    $errors = [];
    $dateIn = $data['date_in'] ?? null;
    $dateOut = $data['date_out'] ?? null;
    $roomId = $data['room_id'] ?? null;
    $status = $data['status'] ?? 'reserved';

    if (is_string($dateIn) && $dateIn !== '' && is_string($dateOut) && $dateOut !== '' && $dateOut <= $dateIn) {
        $errors['date_out'] = 'Дата выезда должна быть позже даты заезда.';
    }

    if (!isset($statusOptions[$status])) {
        $errors['status'] = 'Некорректный статус бронирования.';
    }

    if (
        $status !== 'cancelled'
        && is_string($dateIn) && $dateIn !== ''
        && is_string($dateOut) && $dateOut !== ''
        && (int) $roomId > 0
        && !isset($errors['date_out'])
    ) {
        $availableRooms = $loadAvailableRoomRows($pdo, $dateIn, $dateOut, $recordId);
        if ($availableRooms === []) {
            $errors['room_id'] = 'Нет свободных номеров на выбранные даты.';
        } else {
            $availableIds = array_column($availableRooms, 'id');
            if (!in_array((int) $roomId, $availableIds, true)) {
                $errors['room_id'] = 'Выбранный тип недоступен на выбранные даты.';
            }
        }
    }

    return $errors;
};

$persistBooking = static function (PDO $pdo, array $data, ?int $recordId, string $action, CrudRepository $repository): array {
    $roomId = (int) ($data['room_id'] ?? 0);
    $dateIn = $data['date_in'] ?? null;
    $dateOut = $data['date_out'] ?? null;
    $status = (string) ($data['status'] ?? 'reserved');

    if ($roomId <= 0) {
        return ['room_id' => 'Выберите тип номера.'];
    }

    if (!is_string($dateIn) || $dateIn === '' || !is_string($dateOut) || $dateOut === '') {
        return ['date_in' => 'Укажите даты заезда и выезда.'];
    }

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('SELECT quantity, price FROM rooms WHERE id = :id FOR UPDATE');
        $stmt->execute(['id' => $roomId]);
        $room = $stmt->fetch();

        if (!$room) {
            $pdo->rollBack();
            return ['room_id' => 'Выбранный тип номера не найден.'];
        }

        if ($status !== 'cancelled') {
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM bookings'
                . ' WHERE room_id = :room_id'
                . ' AND status != :cancelled'
                . ' AND id != :current_id'
                . ' AND NOT (date_out <= :date_in OR date_in >= :date_out)'
                . ' FOR UPDATE'
            );
            $stmt->execute([
                'room_id' => $roomId,
                'cancelled' => 'cancelled',
                'current_id' => $recordId ?? 0,
                'date_in' => $dateIn,
                'date_out' => $dateOut,
            ]);
            $booked = (int) $stmt->fetchColumn();

            if ($booked >= (int) $room['quantity']) {
                $pdo->rollBack();
                return ['room_id' => 'Нет свободных номеров на выбранные даты.'];
            }
        }

        if (!isset($data['total']) || (float) $data['total'] <= 0.0) {
            $start = new DateTimeImmutable($dateIn);
            $end = new DateTimeImmutable($dateOut);
            $nights = (int) $start->diff($end)->days;
            if ($nights > 0) {
                $data['total'] = round(((float) $room['price']) * $nights, 2);
            }
        }

        if ($action === 'update') {
            if (($recordId ?? 0) <= 0) {
                $pdo->rollBack();
                return ['room_id' => 'Не удалось определить запись для обновления.'];
            }
            $repository->update($recordId, $data);
        } else {
            $repository->create($data);
        }

        $pdo->commit();
        return [];
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return ['room_id' => 'Не удалось сохранить бронирование.'];
    }
};

$config = [
    'title' => 'Бронирования',
    'table' => 'bookings',
    'primaryKey' => 'id',
    'listTitle' => 'Список бронирований',
    'formTitle' => 'Бронирование',
    'validate' => $validateBooking,
    'persist' => $persistBooking,
    'fields' => [
        [
            'name' => 'date_in',
            'label' => 'Дата заезда',
            'type' => 'date',
            'required' => true,
        ],
        [
            'name' => 'date_out',
            'label' => 'Дата выезда',
            'type' => 'date',
            'required' => true,
        ],
        [
            'name' => 'status',
            'label' => 'Статус',
            'type' => 'select',
            'required' => true,
            'default' => 'reserved',
            'valueType' => 'string',
            'options' => $statusOptions,
        ],
        [
            'name' => 'room_id',
            'label' => 'Тип номера',
            'type' => 'select',
            'required' => true,
            'options' => $loadRoomOptions,
            'listOptions' => $loadRoomListOptions,
        ],
        [
            'name' => 'client_id',
            'label' => 'Клиент',
            'type' => 'select',
            'required' => true,
            'options' => static fn (PDO $pdo): array => $loadOptions($pdo, 'clients', "CONCAT(last_name, ' ', first_name)"),
        ],
        [
            'name' => 'associate_id',
            'label' => 'Сотрудник',
            'type' => 'select',
            'required' => true,
            'options' => static fn (PDO $pdo): array => $loadOptions($pdo, 'associates', "CONCAT(last_name, ' ', first_name)"),
        ],
        [
            'name' => 'total',
            'label' => 'Сумма (руб.)',
            'type' => 'number',
            'step' => '0.01',
            'min' => '0',
            'required' => true,
            'default' => '0',
        ],
    ],
];

require __DIR__ . '/resource.php';
