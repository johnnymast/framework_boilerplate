<?php

use PHPMailer\PHPMailer\PHPMailer;

return [

    'general' => [
      'from' => [
          'address' => 'noreply@pinkhatdancers.site',
          'name' => 'No reply'
      ],
    ],
    'settings' => [
        'port' => $_ENV['MAIL_PORT'] ?? '',
        'host' => $_ENV['MAIL_HOST'] ?? '',
        'user' => $_ENV['MAIL_USERNAME'] ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
        'secure' =>  false, /* Make sure you have your php ssl certs configured if you enable this option */
    ]
];