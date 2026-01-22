<?php

declare(strict_types=1);

$config = [
    'title' => 'Этажи',
    'table' => 'floors',
    'primaryKey' => 'id',
    'listTitle' => 'Список этажей',
    'formTitle' => 'Этаж',
    'fields' => [
        [
            'name' => 'name',
            'label' => 'Название',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'level',
            'label' => 'Номер этажа',
            'type' => 'number',
            'step' => '1',
            'min' => '0',
            'required' => true,
            'valueType' => 'int',
        ],
        [
            'name' => 'view_id',
            'label' => 'Основной вид',
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
    ],
];

require __DIR__ . '/resource.php';
