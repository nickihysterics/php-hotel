<?php

declare(strict_types=1);

$config = [
    'title' => 'Номера',
    'table' => 'rooms',
    'primaryKey' => 'id',
    'listTitle' => 'Список номеров',
    'formTitle' => 'Номер',
    'fields' => [
        [
            'name' => 'number',
            'label' => 'Тип номера',
            'type' => 'text',
            'required' => true,
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
    ],
];

require __DIR__ . '/resource.php';
