<?php
namespace app\components\helpers;

class TextHelper
{
	public static function camel2snake($text)
	{
		return mb_strtolower(
			preg_replace(
				'/([a-zA-Z])(?=[A-Z])/',
				'$1_',
				$text
			)
		);
	}
}