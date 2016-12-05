<?php
namespace app\components\traits;

trait ApplicationTrait
{
	public function getApplication()
	{
		return \app\components\base\Application::getInstance();
	}

	public function getRequest()
	{
		return $this->getApplication()->getRequest();
	}

	public function getContainer()
	{
		return $this->getApplication()->getContainer();
	}
	
	public function getEm()
	{
		return $this->getApplication()->getEm();
	}
	
	public function getResource()
	{
		return $this->getContainer()->get('app.web.resource');
	}

	public function getBitrix()
	{
		return $this->getApplication()->getBitrix();	
	}
}