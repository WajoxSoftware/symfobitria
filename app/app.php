<?php
// load env variables, load composer autoloaders
include(__DIR__ . '/bootstrap.php');

// detect request
$requestUri = $_SERVER['REQUEST_URI'];
$urlParts = parse_url($requestUri);
$requestPath = $urlParts['path'];
$ext = pathinfo($requestPath, PATHINFO_EXTENSION);

define('APP_REQUEST_PATH' , $requestPath);

\wajox\symbitcore\base\Application::createInstance()->prepareHttp();

$loadBitrix = false;

try {
	\wajox\symbitcore\base\Application::getInstance()
		->parseRequest()
		->run();	
} catch(\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	$loadBitrix = true;
}

if ($loadBitrix) {
	include(APP_BASE_DIR . '/bitrixApp.php');
}

