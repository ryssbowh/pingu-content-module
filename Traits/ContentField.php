<?php 

namespace Pingu\Content\Traits;

use Pingu\Content\Entities\ContentField as ContentFieldModel;
use Pingu\Content\Entities\Field;
use Pingu\Content\Events\ContentFieldCreated;

trait ContentField
{
	/**
	 * @inheritDoc
	 */
	public function field()
	{
		return $this->morphOne(Field::class, 'instance');
	}

	/**
	 * Treats the value before it's stored in database
	 */
	public function storeValue($value)
	{
		return $value;
	}

	/**
	 * treats the value after it's retrieved from database
	 */
	public function retrieveValue($value)
	{
		return $value;
	}

	/**
	 * Does this content field define a field
	 * 
	 * @param  string $name
	 * @return bool
	 */
	public function definesField(string $name)
	{
		return isset($this->fieldDefinitions()[$name]);
	}
}