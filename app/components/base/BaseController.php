<?php
namespace app\components\base;

use Symfony\Component\HttpFoundation\Response;
use app\components\bitrix\Bootstrap as BitrixBootstrap;

class BaseController
{
	protected $viewPath;

	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		;
	}

	public function beforeProcess()
	{
		;
	}

	public function afterProcess()
	{
		;
	}

	public function run()
	{
		$actionMethod = $this->getActionMethod();

		return $this->beforeAction($actionMethod)
			->runAction($actionMethod)
			->afterAction($actionMethod);
	}

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
	
	public function getActionMethod()
	{
		$route = $this->getApplication()->getRoute();

		if (!isset($route['_action'])
			|| !method_exists($this, $route['_action'] . 'Action')
		) {
			throw new \Exception('Undefined action');
		}

		return $route['_action'] . 'Action';
	}

	public function getResponse()
	{
		return $this->response;
	}

	protected function getViewPath()
	{
		return $this->viewPath;
	}

	protected function setViewPath($viewPath)
	{
		$this->viewPath;

		return $this;
	}

	protected function createResponse($content, $code = Response::HTTP_OK, $headers = [])
	{
		$this->setResponse(new Response(
				$content,
				$code,
				$headers
			));

		return $this;
	}

	protected function setResponse($response)
	{
		$this->response = $response;

		return $this;
	}

	protected function beforeAction($actionMethod)
	{
		return $this;
	}

	protected function runAction($actionMethod)
	{
		$params = $this->getApplication()->getRoute();

		// unset system params
		$systemParams = ['_controller', '_action', '_format', '_locale', '_route'];
		foreach ($systemParams as $paramName) {
			if (isset($params[$paramName])) {
				unset($params[$paramName]);
			}
		}

		call_user_func_array(
			[$this, $actionMethod], 
			$params
		);

		return $this;
	}

	protected function afterAction($actionMethod)
	{
		return $this;
	}

	protected function render($template, $params = [], $code = Response::HTTP_OK, $headers = [])
	{
		$view = new View(
			APP_BASE_DIR . $this->getViewPath() . '/%name%',
			$template
		);

		return $this->createResponse($view->render($params), $code, $headers);
	}

	protected function redirect($url, $permanent = false)
	{
		$statusCode = $permanent ? Response::HTTP_MOVED_PERMANENTLY : Response::HTTP_FOUND;

		return $this->createResponse('Redirect', $statusCode, ['Location' => $url]);
	}
}