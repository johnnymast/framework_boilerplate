<?php

return [

    'user' => [
        'require_confirmation' => true,
        'entity' => \App\Model\User::class,
        'secret_key' => 'B893yXk53Eru8igRCE6v'
    ],
    "passkeys" => [
        'host' => $_ENV['APP_URL'],
        'domain' => $_ENV['APP_DOMAIN'],
        'enabled' => true,
    ],
    'oauth' => [
        'google' => [
            'client_id' => $_ENV['OAUTH_GOOGLE_CLIENTID'],
            'client_secret' => $_ENV['OAUTH_GOOGLE_CLIENT_SECRET'],
            'enabled' => true,
        ],
        'github' => [
            'client_id' => $_ENV['OAUTH_GITHUB_CLIENTID'],
            'client_secret' => $_ENV['OAUTH_GITHUB_CLIENT_SECRET'],
            'enabled' => true,
        ],
        'discord' => [
            'client_id' => $_ENV['OAUTH_DISCORD_CLIENTID'],
            'client_secret' => $_ENV['OAUTH_DISCORD_CLIENT_SECRET'],
            'enabled' => true,
        ],
        'enabled' => true,
    ],
    'login_blocking' => [
      'max_attempts' => 3,
      'timeout_in_minutes' => 1,
      'enabled' => true,
    ],
    'login' => [
        'redirect_to' => 'home',
    ],
    'register' => [
        'redirect_to' => 'auth.verification',
    ]
];