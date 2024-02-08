<?php

return [
    'layout' => \App\Support\SiteLayoutStyle::from(env('LIVEARCHIVE_LAYOUT', \App\Support\SiteLayoutStyle::default()->value)),
    'sync' => [
      'config' => true,
      'env' => env('LIVEARCHIVE_ENV_SYNC', true)
    ],
    'settings_adapter' => \App\Support\Configurations\Persistence\PersistenceLocation::from(env('LIVEARCHIVE_PERSISTENCE', \App\Support\Configurations\Persistence\PersistenceLocation::default()->value)),
];
