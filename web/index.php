<?php
// patch for bitrix
//$_SERVER['DOCUMENT_ROOT'] = APP_BITRIX_ROOT_DIR;

// load env variables, load composer autoloaders
include(__DIR__ . '/../app/bootstrap.php');

// rewrite DOCUMENT ROOT path for Bitrix
$_SERVER['DOCUMENT_ROOT'] = APP_BITRIX_ROOT_DIR;

// detect request
$requestUri = $_SERVER['REQUEST_URI'];
$urlParts = parse_url($requestUri);
$requestPath = $urlParts['path'];
$ext = pathinfo($requestPath, PATHINFO_EXTENSION);

// redirect if not excecutable file
if (!in_array($ext, ['', 'php', 'phtml', 'html'])
	&& file_exists(APP_BITRIX_ROOT_DIR . realpath($requestPath))
) {
	header('Location: /btxapp/' . $requestPath);
	die();
}

// initialize application
$loadBitrixFiles = !file_exists(APP_BITRIX_ROOT_DIR . $requestPath);

\app\components\base\Application::createInstance([
	'loadBitrixFiles' => $loadBitrixFiles,
])->runWeb();