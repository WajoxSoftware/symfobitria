<?php
namespace app\components\base;

use app\components\helpers\TextHelper;

class BaseModel
{
    public function __set($key, $value)
    {
        $key = $this->getPropertyName($key);

        $this->$key = $value;

        return $this;
    }

    public function __get($key)
    {
    	$key = $this->getPropertyName($key);

    	return $this->$key;
    }

    protected function getPropertyName($key)
    {
        if (property_exists($this, $key)) {
            return $key;
        }

        $snakeKey = TextHelper::camel2snake($key);

        if (property_exists($this, $snakeKey)) {
        	return $snakeKey;
        }

        $msg = 'Property ' . $key
        	. ', ' . $snakeKey
        	. ' does not exists for class '
        	. get_class($this);

        throw new \Exception($msg);
    }
}