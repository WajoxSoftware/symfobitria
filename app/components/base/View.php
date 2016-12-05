<?php
namespace app\components\base;

use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class View
{
	protected $baseDir;

	public function __construct($baseDir)
	{
		$this->setBaseDir($baseDir);
	}

	public function render($viewFile, $params)
	{
		$dir = $this->getViewFileDirectory($viewFile);
		$file = $this->getViewFileName($viewFile);

		$templating = new TemplateEngine(
			new TemplateNameParser(),
			new FilesystemLoader($dir)
		);

		return $templating->render($file,$params);
	}

	protected function getViewFileDirectory($viewFile)
	{
		$pathInfo = pathinfo($viewFile);

		if (!isset($pathInfo['dirname'])
			|| empty($pathInfo['dirname'])
		) {
			return $this->getBaseDir() . '/%name%';
		}

		return $pathInfo['dirname'] . '/%name%';
	}

	protected function getViewFileName($viewFile)
	{
		$pathInfo = pathinfo($viewFile);

		if (!isset($pathInfo['filename'])
			|| empty($pathInfo['filename'])
		) {
			throw new \Exception('Can not determine view file name');
		}

		return  $pathInfo['filename'] . '.' . $pathInfo['extension'];
	}

	protected function setBaseDir($baseDir)
	{
		$this->baseDir = $baseDir;

		return $this;
	}

	protected function getBaseDir()
	{
		return $this->baseDir;
	}
}