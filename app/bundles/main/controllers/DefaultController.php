<?php
namespace app\bundles\main\controllers;

class DefaultController extends \app\components\base\BaseController
{
	protected $viewPath = '/bundles/main/views/default';

	public function indexAction()
	{
		return $this->render('index.php', ['message' => 'Hello World!']);
	}
}