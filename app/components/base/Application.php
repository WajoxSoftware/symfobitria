<?php
namespace app\components\base;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Config\FileLocator;

use Symfony\Component\Routing\Loader\YamlFileLoader as RoutingYamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as DiYamlFileLoader;

use Symfony\Component\Yaml\Yaml;
use app\components\bitrix\base\Application as BitrixApplication;
use app\components\exceptions\NotFoundException;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Application
{
	const CONFIG_ROUTES_YAML = 'routes.yml';
	const CONFIG_SERVICES_YAML = 'services.yml';

	protected $container;
	protected $controller;
	protected $request;
	protected $response;
	protected $route;
	protected $bitrix;
	protected $settings;
	protected $em;

	/* @var \app\components\base\Application */
	protected static $instance;

	public static function createInstance($settings = [])
	{
		self::setInstance(new self($settings));

		return self::getInstance();
	}

	public static function getInstance()
	{
		return self::$instance;
	}

	public function loadConfig($yamlFile)
	{
		return Yaml::parse(file_get_contents(APP_CONFIG_DIR . '/' . $yamlFile));
	}

	public function run()
	{
		try {
			$this
				->initController()
				->beforeProcess()
				->process()
				->afterProcess();			
		} catch (NotFoundException $e) {
			return $this->renderNotFoundError();
		} catch(\Exception $e) {
			return $this->renderInternalError();
		}

		return $this;

	}

	public function __construct($settings = [])
	{
		$this->initSettings($settings)
			->initBitrix()
			->initContainer()
			->initBitrix();
	}

	public function prepareHttp()
	{
		$this->initRequest()
			->initResponse();

		return $this;
	}

	public function parseRequest()
	{
		$this->initRoute();

		return $this;
	}

	public function getEm()
	{
		if (!$this->em) {
			$this->initEm();
		}

		return $this->em;
	}

	public function getSettings()
	{
		return $this->settings;
	}

	public function getContainer()
	{
		return $this->container;
	}

	public function getRequest()
	{
		return $this->request;
	}

	public function setResponse($response)
	{
		$this->response = $response;

		return $this;
	}

	public function getRoute()
	{
		return $this->route;
	}

	public function getBitrix()
	{
		return $this->bitrix;
	}

	public function runConsoleCommand()
	{
		$app = new \Symfony\Component\Console\Application();

		$commands = require(APP_CONFIG_DIR . '/commands.php');

		foreach ($commands as $command) {
			$app->add($command);	
		} 
		
		$app->run();
	}

	protected static function setInstance($instance)
	{
		self::$instance = $instance;
	}

	protected function initController()
	{
		$controllerClassName = $this->findController();

		$controller = new $controllerClassName();

		$this->setController($controller);

		return $this;
	}

	protected function process()
	{
		$this->runController();

		return $this;
	}

	protected function getDbConfig()
	{
		return [
		    'driver'   => $_ENV['DOCTRINE_DRIVER'],
		    'host' => $_ENV['DB_HOST'],
		    'user'     => $_ENV['DB_USER'],
		    'password' => $_ENV['DB_PASSWORD'],
		    'dbname'   => $_ENV['DB_NAME'],
		    'charset' => 'utf8',
		];
	}

	protected function getOrmConfig()
	{
		$cache = new \Doctrine\Common\Cache\ArrayCache();
		// Doctrine ORM
		$ormconfig = new \Doctrine\ORM\Configuration();
		$ormconfig->setQueryCacheImpl($cache);
		$ormconfig->setProxyDir(APP_BASE_DIR . '/model/EntityProxy');
		$ormconfig->setProxyNamespace('EntityProxy');
		$ormconfig->setAutoGenerateProxyClasses(true);
		 
		// ORM mapping by Annotation
		AnnotationRegistry::registerLoader('class_exists');

		$driver = new AnnotationDriver(
		    new AnnotationReader(),
		    [APP_BASE_DIR . '/model/Entity']
		);

		$ormconfig->setMetadataDriverImpl($driver);
		$ormconfig->setMetadataCacheImpl($cache);

		return $ormconfig;
	}

	protected function initEm()
	{
		$em = EntityManager::create(
			$this->getDbConfig(),
			$this->getOrmConfig()
		);

		// The Doctrine Classloader
		$classLoader = new \Doctrine\Common\ClassLoader('Entity', APP_BASE_DIR . '/model');
		$classLoader->register();
		
		$this->setEm($em);

		return $this;
	}

	protected function setEm($em)
	{
		$this->em = $em;

		return $this;
	}

	protected function initSettings($settings)
	{
		$this->settings = new Settings($settings);

		return $this;
	}

	protected function setBitrix($bitrix)
	{
		$this->bitrix = $bitrix;

		return $this;
	}

	protected function initBitrix()
	{
		$this->setBitrix(new BitrixApplication($this->getSettings()->loadBitrixFiles));

		return $this;
	}

	protected function setContainer($container)
	{
		$this->container = $container;

		return $this;
	}

	protected function initContainer()
	{
		$container = new ContainerBuilder();
		$loader = new DiYamlFileLoader($container, new FileLocator(APP_CONFIG_DIR));
		$loader->load(self::CONFIG_SERVICES_YAML);

		$this->setContainer($container);

		return $this;
	}

	protected function initRequest()
	{
		$this->setRequest(Request::createFromGlobals());

		return $this;
	}

	protected function setRequest($request)
	{
		$this->request = $request;

		return $this;
	}

	protected function getRequestContext()
	{
		$context = new RequestContext();
		$context->fromRequest($this->getRequest());

		return $context;
	}

	protected function getRouteCollection()
	{
		$locator = new FileLocator(array(APP_CONFIG_DIR));
		$loader = new RoutingYamlFileLoader($locator);

		return $loader->load(self::CONFIG_ROUTES_YAML);
	}

	protected function runController()
	{
		$this->getController()->run();

		$response = $this->getController()->getResponse();

		$this->setResponse($response);

		return $this;
	}

	protected function sendResponse()
	{
		if (!$this->getResponse()) {
			return $this;
		}

		$this->getResponse()->prepare($this->getRequest());

		$this->getResponse()->send();

		return $this;
	}

	protected function initResponse()
	{
		// set empty default response
		$this->setResponse(new Response(
		    '',
		    Response::HTTP_OK,
		    array('content-type' => 'text/html')
		));

		return $this;
	}

	protected function initRoute()
	{
		$matcher = new UrlMatcher(
			$this->getRouteCollection(),
			$this->getRequestContext()
		);

		$this->setRoute($matcher->matchRequest($this->getRequest()));

		return $this;
	}

	protected function setController($controller)
	{
		$this->controller = $controller;

		return $this;
	}

	protected function getController()
	{
		return $this->controller;
	}

	protected function setRoute($route)
	{
		$this->route = $route;

		return $this;
	}

	protected function getResponse()
	{
		return $this->response;
	}

	protected function findController()
	{
		$route = $this->getRoute();

		if (!isset($route['_controller'])
			//|| !class_exists($route['_controller'])
		) {
			throw new NotFoundException('Controller does not exists');
		}

		return $route['_controller']; 
	}

	protected function beforeProcess()
	{
		$this->getController()->beforeProcess();
		$this->getBitrix()->prolog();

		return $this;
	}

	protected function afterProcess()
	{
		$this->getController()->afterProcess();
		$this->getBitrix()->epilog();

		$this->sendResponse();

		return $this;
	}

	protected function renderNotFoundError()
	{
		$msg = '<h1>404 Not found</h1>';
		$this->createResponse($msg, Response::HTTP_NOT_FOUND);
	}

	protected function renderInternalError()
	{
		$msg = '<h1>500 Internal Server Error</h1>';
		$this->createResponse($msg, Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	protected function createResponse($content, $code = Response::HTTP_OK, $headers = [])
	{
		$this->setResponse(new Response(
				$content,
				$code,
				$headers
			));
	}
}