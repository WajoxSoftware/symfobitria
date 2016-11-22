<?php
namespace app\components\base;

class Settings
{
	protected $data = [];

	public function __construct($data = [])
	{
		$this->data = $data;
	}

	public function __get($name)
	{
		if (!isset($this->data[$name])) {
			return;
		}

		return $this->data[$name];
	}

	public function set($name, $value)
	{
		$this->data[$name] = $value;
	}
}