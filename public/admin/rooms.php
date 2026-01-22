<?php

declare(strict_types=1);

$config = [
    'title' => 'Номера',
    'table' => 'rooms',
    'primaryKey' => 'id',
    'listTitle' => 'Список типов номеров',
    'formTitle' => 'Тип номера',
    'fields' => [
        [
            'name' => 'number',
            'label' => 'Тип номера',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'category_id',
            'label' => 'Категория',
            'type' => 'select',
            'required' => true,
            'options' => static function (PDO $pdo): array {
                $rows = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
                $options = [];
                foreach ($rows as $row) {
                    $options[$row['id']] = $row['name'];
                }
                return $options;
            },
        ],
        [
            'name' => 'floor_id',
            'label' => 'Этаж',
            'type' => 'select',
            'required' => true,
            'options' => static function (PDO $pdo): array {
                $rows = $pdo->query('SELECT id, name, level FROM floors ORDER BY level')->fetchAll();
                $options = [];
                foreach ($rows as $row) {
                    $options[$row['id']] = sprintf('%s', $row['name']);
                }
                return $options;
            },
        ],
        [
            'name' => 'view_id',
            'label' => 'Вид из номера',
            'type' => 'select',
            'required' => true,
            'options' => static function (PDO $pdo): array {
                $rows = $pdo->query('SELECT id, name FROM room_views ORDER BY name')->fetchAll();
                $options = [];
                foreach ($rows as $row) {
                    $options[$row['id']] = $row['name'];
                }
                return $options;
            },
        ],
        [
            'name' => 'bed_count',
            'label' => 'Спальных мест',
            'type' => 'number',
            'step' => '1',
            'min' => '1',
            'required' => true,
            'valueType' => 'int',
        ],
        [
            'name' => 'bathroom_separate',
            'label' => 'Раздельный санузел',
            'type' => 'select',
            'required' => true,
            'default' => 0,
            'valueType' => 'int',
            'options' => [
                1 => 'Да',
                0 => 'Нет',
            ],
        ],
        [
            'name' => 'bathroom_type_id',
            'label' => 'Тип санузла',
            'type' => 'select',
            'required' => true,
            'options' => static function (PDO $pdo): array {
                $rows = $pdo->query('SELECT id, name FROM bathroom_types ORDER BY name')->fetchAll();
                $options = [];
                foreach ($rows as $row) {
                    $options[$row['id']] = $row['name'];
                }
                return $options;
            },
        ],
        [
            'name' => 'price',
            'label' => 'Цена (руб.)',
            'type' => 'number',
            'step' => '0.01',
            'min' => '0',
            'required' => true,
        ],
        [
            'name' => 'quantity',
            'label' => 'Количество',
            'type' => 'number',
            'step' => '1',
            'min' => '0',
            'required' => true,
            'valueType' => 'int',
        ],
        [
            'name' => 'photo',
            'label' => 'Фото (имя файла)',
            'type' => 'text',
            'required' => false,
        ],
    ],
];

require __DIR__ . '/resource.php';
