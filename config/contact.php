<?php

return [
    'default_email_receiver' => env('MAIL_FROM_ADDRESS'),
    'save_messages' => true,

    'emails' => [
        // contact@domain.com
    ],

    'numbers' => [
        // +32 1111  111 111
    ],

    'socials' => [

    ],

    'addresses' => [
        [
            'name' => 'Office',
            'street' => '',
            'number' => '',
            'postcode' => '',
            'city' => '',
            'country' => '',
        ],
    ],
    'schedule' => [
        [
            'days' => '',
            'hours' => '',
        ]
    ],
];
