<?php

namespace Pingu\Content\Exceptions;

class ContentFieldNotRegistered extends \Exception{

	public static function create($name)
	{
		return new static("Content Field $name isn't registered");
	}

}