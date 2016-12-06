<?php
include(__DIR__ . '/../vendor/autoload.php');

include(__DIR__ . '/bootstrap/constants.php');
include(__DIR__ . '/bootstrap/env.php');

global $USER;
global $APPLICATION;
global $DB;

$GLOBALS['DBType'] = $_ENV['DB_TYPE'];
$GLOBALS['$DBHost'] = $_ENV['DB_HOST'];
$GLOBALS['$DBLogin'] = $_ENV['DB_USER'];
$GLOBALS['$DBPassword'] = $_ENV['DB_PASSWORD'];
$GLOBALS['$DBName'] = $_ENV['DB_NAME'];
$GLOBALS['Application'] = null;
$GLOBALS['USER'] = null;
$GLOBALS['DB'] = null;
$GLOBALS['MESS'] = null;

$_SERVER['DOCUMENT_ROOT'] = APP_BITRIX_ROOT_DIR;
putenv('DOCUMENT_ROOT', APP_BITRIX_ROOT_DIR);
