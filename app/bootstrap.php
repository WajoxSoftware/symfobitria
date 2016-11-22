<?php

include(__DIR__ . '/vendor/autoload.php');

/* 
// Directory definitions examples

// bitrix directories
define('APP_BITRIX_ROOT_DIR', __DIR__ . '/../bapp');
define('APP_BITRIX_DIR', APP_BITRIX_ROOT_DIR . '/bitrix');

// app directories
define('APP_BASE_DIR', __DIR__);
define('APP_BUNDLES_DIR', APP_BASE_DIR . '/bundles');
define('APP_CONFIG_DIR', __DIR__ . '/../config');

// www directory
define('APP_WEB_DIR', __DIR__ . '/../web');

// Run application

\app\components\base\Application::createInstance();
*/
include(__DIR__ . '/bootstrap/constants.php');
include(__DIR__ . '/bootstrap/env.php');

$GLOBALS['DBType'] = $_ENV['DB_TYPE'];
$GLOBALS['$DBHost'] = $_ENV['DB_HOST'];
$GLOBALS['$DBLogin'] = $_ENV['DB_USER'];
$GLOBALS['$DBPassword'] = $_ENV['DB_PASSWORD'];
$GLOBALS['$DBName'] = $_ENV['DB_NAME'];
$GLOBALS['Application'] = null;
$GLOBALS['USER'] = null;
$GLOBALS['DB'] = null;
$GLOBALS['MESS'] = null;
