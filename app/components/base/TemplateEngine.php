<?php
namespace app\components\base;

class TemplateEngine extends \Symfony\Component\Templating\PhpEngine
{
	protected function getApplication()
	{
		return \app\components\base\Application::getInstance();
	}
}