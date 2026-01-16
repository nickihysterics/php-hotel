<?php

declare(strict_types=1);

$config = [
    'title' => 'Категории',
    'table' => 'categories',
    'primaryKey' => 'id',
    'listTitle' => 'Список категорий',
    'formTitle' => 'Категория',
    'fields' => [
        [
            'name' => 'name',
            'label' => 'Название',
            'type' => 'text',
            'required' => true,
        ],
    ],
];

require __DIR__ . '/resource.php';
