<?php
namespace app\components\base;

use Symfony\Component\HttpFoundation\Response;
use app\components\bitrix\Bootstrap as BitrixBootstrap;

class BaseController
{
	protected $layout = 'layouts/main.php';

	use \app\components\traits\ApplicationTrait;
	
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

		$this->beforeAction($actionMethod);
		$this->runAction($actionMethod);
		$this->afterAction($actionMethod);
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
		$view = new View($this->getViewsDir());

		$response = $view->render(
			$this->getViewPath($template),
			$params
		);

		if ($this->getLayout()) {
			$response = $view->render(
				$this->getLayoutPath(),
				['content' => $response]
			);
		}

		return $this->createResponse($response, $code, $headers);
	}

	protected function redirect($url, $permanent = false)
	{
		$statusCode = $permanent ? Response::HTTP_MOVED_PERMANENTLY : Response::HTTP_FOUND;

		return $this->createResponse('Redirect', $statusCode, ['Location' => $url]);
	}

	protected function getLayout()
	{
		return $this->layout;
	}

	protected function setLayout($layout)
	{
		$this->layout = $layout;

		return $this;
	}

	protected function getClassName()
	{
		return get_class($this);
	}

	protected function getBundleDir()
	{
		$parts = explode('\\', $this->getClassName());
		$parts = array_slice($parts, 0, -2);

		return APP_ROOT_DIR . '/' . implode('/', $parts);
	}

	protected function getViewsDir()
	{
		return $this->getBundleDir() . '/views';
	}

	protected function getViewPath($viewFile)
	{
		return $this->getViewsDir() . '/' . $this->getId() . '/' . $viewFile;
	}

	protected function getLayoutPath()
	{
		return $this->getViewsDir() . '/' . $this->getLayout();
	}

	protected function getId()
	{
		$id = explode('\\', $this->getClassName());
		$id = end($id);
		

		$id = str_replace('Controller', '', $id);

		return mb_strtolower($id);
	}
}