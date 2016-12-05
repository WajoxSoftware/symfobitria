<?php
namespace app\components\bitrix\base;

use app\components\exceptions\NotFoundException;

class PathParser
{
	public function getBitrixPath($path)
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

			if ($ext == ''
				&& $ext != 'php'
				&& !is_dir(APP_BITRIX_ROOT_DIR . $resultPath)
			) {
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
		
		return '/index.php';
	}

	protected function isBitrixPath($path)
	{
		$realPath = realpath($path);
		$bitrixRealPath = realpath(APP_BITRIX_ROOT_DIR);

		return strpos($realPath, $bitrixRealPath) === 0
			&& (file_exists($realPath) || file_exists($realPath . '/index.php'));
	}
}
