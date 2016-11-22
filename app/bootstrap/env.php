<?php
(new Dotenv\Dotenv(APP_CONFIG_DIR, '.env'))->load();

define('APP_ENV', $_ENV['APP_ENV']);

