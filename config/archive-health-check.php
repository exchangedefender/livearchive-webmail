<?php

return [
    'exclude_htmx' => true,
    'excluded_routes' => ['setup.*'],
    'route' => [
        'prefix' => '/health',
        'health_redirect_to' => 'mailbox.list',
    ],
];
