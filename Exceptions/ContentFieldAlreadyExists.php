<?php

namespace Pingu\Content\Exceptions;

class ContentFieldAlreadyExists extends \Exception{

	public static function create($name)
	{
		return new static("Content Field $name is already registered");
	}

}