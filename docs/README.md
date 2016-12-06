# Get Started

## How to work with bitrix api

Use Application instance instead of global variables e.g.:

```
# in views
$this->getApplication()->getBitrix()->APPLICATION; # global $APPLICATION
# on other places
\app\components\base\Application::getInstance()->getBitrix()->APPLICATION;
```

This way you can access other bitrix global variables.

## How to use symfony components on bitrix side
There is simple way to access all application components from bitrix side, just call \app\components\base\Application::getInstance() e.g.:

```
# access request object from bitrix side
\app\components\base\Application::getInstance()->getRequest(); # this is request object
```

## How to create controller

There is example of controller with view

```
# app/bundles/hello/controllers/DefaultController

<?php
namespace app\bundles\hello\controllers;

class DefaultController extends \app\components\base\BaseController
{
    # path to views directory
	protected $viewPath = '/bundles/hello/views/default'; 

	public function indexAction()
	{
		# view params
		$params = ['message' => 'Hello World!'];
		# render index.php view which located in $viewPath
		return $this->render('index.php', $params);
	}
}
```

```
# app/bundles/hello/views/index.php

# render message from controller
<?= $message ?>
```

```
# config/routes.yml

# add route for our controller action
# url: http://our_site.domain/hello

hello:
    path: /hello
    defaults: { _controller: '\app\bundles\hello\controllers\DefaultController', _action: 'index' }
```
