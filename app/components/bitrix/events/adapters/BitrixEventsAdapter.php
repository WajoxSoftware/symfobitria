<?php
namespace app\components\bitrix\events\adapters;

class BitrixEventsAdapter extends \app\components\base\AdapterAbstract
{
	protected function init()
	{
		;
	}
	
	public function bindEvents()
	{
		foreach ($this->getConfigParam('modules') as $moduleId => $events) {
			foreach ($events as $event => $handlers) {
				foreach ($handlers as $handler) {
					$this->bindEvent($moduleId, $event, $handler);
				}
			}
		}
	}

	public function bindEvent($moduleId, $event, $eventClass)
	{
		if (!class_exists($eventClass)) {
			throw new \Exception('Event handler "' . $eventClass . '" does not exists');
		}

		\AddEventHandler($moduleId, $event, [$eventClass, 'onEvent']);
	}
}