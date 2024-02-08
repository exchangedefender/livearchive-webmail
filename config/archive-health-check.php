<?php

return [
    'exclude_htmx' => true,
    'excluded_routes' => ['setup.*', 'settings'],
    'route' => [
        'prefix' => '/health',
        'health_redirect_to' => 'mailbox.list',
    ],
];
