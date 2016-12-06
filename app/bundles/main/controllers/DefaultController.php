<?php
namespace app\bundles\main\controllers;

class DefaultController extends \wajox\symbitcore\base\BaseController
{
	public function indexAction()
	{
		return $this->render('index.php', ['message' => 'Hello World!']);
	}
}