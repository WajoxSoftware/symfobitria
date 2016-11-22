<?php
namespace app\components\base;

abstract class AdapterAbstract
{
	protected $config = [];

	public function __construct($config)
	{
		$this->setConfig($config)->init();
	}

	protected function getConfig()
	{
		return $this->config;
	}

	protected function setConfig($config)
	{
		$this->config = $config;

		return $this;
	}

	protected function getConfigParam($name)
	{
		if (isset($this->config[$name])) {
			return $this->config[$name];
		}
	}

	abstract protected function init();
}