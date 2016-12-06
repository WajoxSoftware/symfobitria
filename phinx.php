<?php
require __DIR__ . '/app/bootstrap.php';

$params = [
    'paths' => [
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
    ],
    'environments' => [
       'default_database' => 'default',
       'default' => [
            'adapter' => $_ENV['DB_TYPE'],
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASSWORD'],
            'table_prefix' => $_ENV['DB_PHINX_TABLE_PREFIX'],
            'port' => '3306',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
       ],
    ],
];

return $params;
