<?php

return [
    'port' => $_ENV['DB_PORT'] ?? 3306,
    'host' => $_ENV['DB_HOST'] ?? '',
    'user' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASS'] ?? '',
    'dbname' => $_ENV['DB_DATABASE'] ?? '',
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
    'charset'  => 'utf8',
];