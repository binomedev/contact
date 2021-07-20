<?php

return [
    'default_to' => '',
    'save_messages' => true,
    'save_subscribers' => true,
    'priority' => 3,

    'enable_gmail_api' => env('ENABLE_GMAIL_API', false),
    'enable_legacy_support' => env('CONTACT_ENABLE_LEGACY_SUPPORT', false),
];
