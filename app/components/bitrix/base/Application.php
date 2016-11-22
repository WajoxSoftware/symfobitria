<?php
namespace app\components\bitrix\base;

class Application
{
	protected $content = [];
	protected $loadBitrixFiles = true;

	public function __construct($loadBitrixFiles = true)
	{
		$this->setLoadBitrixFiles($loadBitrixFiles);
	}

	public function __get($name)
	{
		$name = strtoupper($name);
		
		if (isset($GLOBALS[$name])) {
			return $GLOBALS[$name];
		}
	}

	public function getContent($name)
	{
		if (!isset($this->content[$name])) {
			return;
		}

		return $this->content[$name];
	}

	public function setContent($name, $value)
	{
		$this->content[$name] = $value;

		return $this;
	}

	public function prolog()
	{
		if (!$this->getLoadBitrixFiles()) {
			return;
		}

		$this->unsetBxStateParams();

		$this->includeBitrixFiles([
			'/modules/main/include.php'
		]);

		global $USER;
		global $APPLICATION;
		global $DB;
		
		extract($GLOBALS);

		\CMain::PrologActions();

		define('START_EXEC_PROLOG_BEFORE_1', microtime());
		define('START_EXEC_PROLOG_AFTER_1', microtime());
		define('START_EXEC_PROLOG_AFTER_2', microtime());

		$GLOBALS['BX_STATE'] = 'WA';
	}

	public function epilog()
	{
		if (!$this->getLoadBitrixFiles()
			|| !defined("B_PROLOG_INCLUDED")
			|| !B_PROLOG_INCLUDED !== true
		) {
			return;
		}

		define("START_EXEC_EPILOG_BEFORE_1", microtime());
		define("START_EXEC_EPILOG_AFTER_1", microtime());

		$GLOBALS["BX_STATE"] = "EA";

		global $USER;
		global $APPLICATION;
		global $DB;
		extract($GLOBALS);

		foreach (GetModuleEvents("main", "OnEpilog", true) as $arEvent) {
			ExecuteModuleEventEx($arEvent);
		}

		foreach (GetModuleEvents("main", "OnAfterEpilog", true) as $arEvent) {
			ExecuteModuleEventEx($arEvent);
		}

		$DB->Disconnect();

		\CMain::ForkActions();
	}

	public function includeBitrixFiles($files)
	{	
		chdir(APP_BITRIX_DIR);
		
		foreach ($files as $file) {
			global $APLICATION;
			global $USER;
			global $DB;

			extract($GLOBALS);
			include(APP_BITRIX_DIR . $file);
		}
		
		chdir(APP_BASE_DIR);
	}

	protected function unsetBxStateParams()
	{
		if (isset($_REQUEST['BX_STATE'])) unset($_REQUEST['BX_STATE']);
		if (isset($_GET['BX_STATE'])) unset($_GET['BX_STATE']);
		if (isset($_POST['BX_STATE'])) unset($_POST['BX_STATE']);
		if (isset($_COOKIE['BX_STATE'])) unset($_COOKIE['BX_STATE']);
		if (isset($_FILES['BX_STATE'])) unset($_FILES['BX_STATE']);
	}

	protected function setLoadBitrixFiles($loadBitrixFiles)
	{
		$this->loadBitrixFiles = $loadBitrixFiles;

		return $this;
	}

	protected function getLoadBitrixFiles()
	{
		return $this->loadBitrixFiles;
	}
}