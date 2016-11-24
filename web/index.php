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

define('APP_REQUEST_PATH' , $requestPath);

// redirect if not excecutable file
if (!in_array($ext, ['', 'php', 'phtml', 'html'])
	&& file_exists(APP_BITRIX_ROOT_DIR . realpath($requestPath))
	&& APP_BITRIX_ROOT_DIR . realpath($requestPath) != APP_BITRIX_ROOT_DIR
) {
	header('Location: /btxapp/' . $requestPath);
	die();
}

\app\components\base\Application::createInstance()->prepareHttp();

$loadBitrix = false;

try {
	\app\components\base\Application::getInstance()
		->parseRequest()
		->run();	
} catch(\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	$loadBitrix = true;
}

if ($loadBitrix) {
	include(APP_BASE_DIR . '/bitrixApp.php');
}

