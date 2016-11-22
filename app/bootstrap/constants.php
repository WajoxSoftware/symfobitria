<?php
// app directories
define('APP_ROOT_DIR', realpath(__DIR__ . '/../../'));
define('APP_BASE_DIR', APP_ROOT_DIR . '/app');
define('APP_BUNDLES_DIR', APP_BASE_DIR . '/bundles');
define('APP_CONFIG_DIR', APP_ROOT_DIR . '/config');

// bitrix directories
define('APP_BITRIX_ROOT_DIR', APP_ROOT_DIR . '/btxapp');
define('APP_BITRIX_DIR', APP_BITRIX_ROOT_DIR . '/bitrix');

// www directory
define('APP_WEB_DIR', APP_ROOT_DIR . '/web');