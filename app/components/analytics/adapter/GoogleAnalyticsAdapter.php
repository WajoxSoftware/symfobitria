<?php
namespace app\components\analytics\adapter;

use TheIconic\Tracking\GoogleAnalytics\Analytics;
use app\components\base\Application;

class GoogleAnalyticsAdapter extends \app\components\base\AdapterAbstract
{
	protected $api;

	protected function init()
	{
		;
	}

	protected function setApi($api)
	{
		$this->api = $api;

		return $this;
	}

	protected function getRequest()
	{
		return Application::getInstance()->getRequest();
	}

	public function getRequestClientIp()
	{
		$ip = $this->getRequest()->server->get('HTTP_X_FORWARDED_FOR');

		if ($ip) {
			return $ip;
		}

		return $this->getRequest()->server->get('REMOTE_ADDR');
	}

	public function getCookieClientId()
	{
		$ga = $this->getRequest()->cookies->get('_ga');

		if (!$ga) {
			return;;
		}

		$ga = explode('.', $ga);

		if (!isset($ga[2])
			|| !isset($ga[3])
		) {
			return;
		}

        return $ga[2].'.'.$ga[3];
	}

	public function createApi()
	{
		$trackingId = $this->getConfigParam('trackingId');
		$version = '1';
		$path = '/';

		$api = new Analytics(true);

		$api
		    ->setProtocolVersion($version)
		    ->setTrackingId($trackingId)
		    ->setDocumentPath($path)
		    ->setClientId('');

		$this->setApi($api);

		return $this;
	}

	public function setClientId($clientId)
	{
		if (empty($clientId)) {
			$clientId = '';
		}

		$this->getApi()->setClientId($clientId);
		
		return $this;
	}

	public function setUserId($userId)
	{
		if (empty($userId)) {
			return $this;
		}
		
		$this->getApi()->setUserId($userId);
		
		return $this;
	}

	public function getApi()
	{
		return $this->api;
	}

	public function sendEvent($category, $action, $label = null, $value = null)
	{
		$this->getApi()->setEventCategory($category)
    		->setEventAction($action);

    	if ($label !== null) {
    		$this->getApi()->setEventLabel($label);
    	}

    	if ($value !== null) {
    		$this->getApi()->setEventValue($value);
    	}

    	$this->getApi()->sendEvent();

    	return $this;
	}
}