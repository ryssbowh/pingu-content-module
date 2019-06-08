<?php

namespace Pingu\Content\Exceptions;

use Pingu\Content\Contracts\ContentFieldContract;

class ContentContractMissing extends \Exception{

	public static function create($class)
	{
		return new static("$class doesn't implement ".ContentFieldContract::class);
	}

}