<?php

declare(strict_types=1);

$config = [
    'title' => 'Клиенты',
    'table' => 'clients',
    'primaryKey' => 'id',
    'listTitle' => 'Список клиентов',
    'formTitle' => 'Клиент',
    'fields' => [
        [
            'name' => 'last_name',
            'label' => 'Фамилия',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'first_name',
            'label' => 'Имя',
            'type' => 'text',
            'required' => true,
        ],
        [
            'name' => 'middle_name',
            'label' => 'Отчество',
            'type' => 'text',
            'required' => false,
        ],
        [
            'name' => 'birth_date',
            'label' => 'Дата рождения',
            'type' => 'date',
            'required' => true,
        ],
        [
            'name' => 'phone',
            'label' => 'Телефон',
            'type' => 'text',
            'required' => true,
        ],
    ],
];

require __DIR__ . '/resource.php';
