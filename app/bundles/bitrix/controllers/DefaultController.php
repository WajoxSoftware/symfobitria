<?php
namespace app\bundles\bitrix\controllers;

use app\components\exceptions\NotFoundException;

class DefaultController extends \app\components\base\BaseController
{
	protected $viewPath = '/bundles/bitrix/views/default';

	public function indexAction()
	{
		include(APP_BITRIX_ROOT_DIR . '/index.php');
	}

	public function pageAction($path = '')
	{
		$bitrixFilePath = $this->getBitrixPath('/' . $path);

		include($bitrixFilePath);
	}

	protected function getBitrixPath($path)
	{
		$fullPath = APP_BITRIX_ROOT_DIR . $this->computeBitrixPath($path);

		if (!file_exists($fullPath)
			|| !$this->isBitrixPath($fullPath)
		) {
			throw new NotFoundException('Page not found');
		}

		return $fullPath;
	}

	protected function computeBitrixPath($path)
	{
		$resultPath = $path;
		
		while(!in_array($resultPath, ['', '.', '\\', '/'])) {
			$ext = pathinfo($resultPath, PATHINFO_EXTENSION);

			if ($ext != 'php' && !is_dir(APP_BITRIX_ROOT_DIR . $resultPath)) {
				$resultPath = dirname($resultPath);
			}

			if (file_exists(APP_BITRIX_ROOT_DIR . $resultPath . '/index.php')) {
				return $resultPath . '/index.php';
			}

			if (file_exists(APP_BITRIX_ROOT_DIR . $resultPath)) {
				return $resultPath;
			}

			$resultPath = dirname($resultPath);
		}
	}

	protected function isBitrixPath($path)
	{
		$realPath = realpath($path);
		$bitrixRealPath = realpath(APP_BITRIX_ROOT_DIR);
		$fullPath = APP_BITRIX_ROOT_DIR . $realpath;

		return strpos($realPath, $bitrixRealPath) === 0
			&& (file_exists($fullPath) || file_exists($fullPath . '/index.php'));
	}
}