<?php
if (!defined('APP_BASE_DIR')) {
	die('Error!');
}

if (sizeof($_GET) == 0) {
	$_SERVER['REQUEST_URI'] = rtrim($_SERVER['REQUEST_URI'], '/') . '/';	
}

$pathParser = new app\components\bitrix\base\PathParser();
$bitrixFilePath = $pathParser->getBitrixPath(APP_REQUEST_PATH);

include($bitrixFilePath);
