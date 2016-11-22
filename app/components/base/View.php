<?php
namespace app\components\base;

use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class View
{
	protected $directory;
	protected $templateName;

	public function __construct($directory, $templateName)
	{
		$this->setDirectory($directory)
			->setTemplateName($templateName);
	}

	public function render($params)
	{
		$loader = new FilesystemLoader($this->getDirectory());

		$templating = new TemplateEngine(new TemplateNameParser(), $loader);

		return $templating->render($this->getTemplateName(), $params);
	}
	
	protected function setDirectory($directory)
	{
		$this->directory = $directory;

		return $this;
	}

	protected function getDirectory()
	{
		return $this->directory;
	}

	protected function setTemplateName($templateName)
	{
		$this->templateName = $templateName;

		return $this;
	}

	protected function getTemplateName()
	{
		return $this->templateName;
	}
}