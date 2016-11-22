<?php
namespace app\components\base;

use app\components\base\Application;

class BaseAdapterManager
{
	protected $adapter;

	public function __construct($adapterClassName, $configFile)
	{
		$this->createAdapter($adapterClassName, $configFile);
	}

	public function getAdapter()
	{
		return $this->adapter;
	}

	public function __call($name, $arguments)
	{
		return call_user_func_array([$this->getAdapter(), $name], $arguments);
	}

	protected function createAdapter($adapterClassName, $configFile)
	{
		$config = Application::getInstance()->loadConfig($configFile);

		$adapter = new $adapterClassName($config);
		
		$this->setAdapter($adapter);
	}

	protected function setAdapter($adapter)
	{
		$this->adapter = $adapter;

		return $this;
	}
}
