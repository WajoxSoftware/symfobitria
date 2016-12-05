<?php
namespace app\components\bitrix\base;

class BaseController extends \app\components\base\BaseController
{
	public function init()
	{
		parent::init();

		$this->getApplication()->getSettings()->loadBitrixFiles = true;
	}

	public function can($action)
	{
		$this->getBitrixUser()->CanDoOperation($action);
	}

	public function getBitrixUser()
	{
		return $this->getBitrix()->USER;
	}

	public function getBitrixApp()
	{
		return $this->getBitrix()->APPLICATION;
	}
}