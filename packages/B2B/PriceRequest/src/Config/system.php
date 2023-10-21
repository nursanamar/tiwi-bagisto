<?php

return [
    [
        'key' => 'price_request',
        'name' => 'Price Request',
        'sort' => 1
    ],
    [
        'key' => 'price_request.settings',
        'name' => 'Price Request Setting',
        'sort' => 1
    ],
    [
        'key' => 'price_request.settings.contacts',
        'name' => 'Contact Info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'phone',
                'title' => 'Phone Number',
                'type' => 'text',
                'validation' => 'required'
            ]
        ]
    ]
];