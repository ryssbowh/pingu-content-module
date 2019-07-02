<?php

namespace Pingu\Content\Contracts;

use Pingu\Forms\Contracts\Models\FormableContract;

interface ContentFieldContract extends FormableContract{

	/**
	 * Define the field relationship, this needs to be a morphOne which will point to the generic field (model Field)
	 * @return Relation
	 */
	public function field();

	/**
	 * Field class used to display this field in a form
	 * @return string
	 */
	public function fieldType();

	/**
	 * Field definition, return an array as expected by a Form
	 * @return array
	 */
	public function fieldDefinition();

	/**
	 * Field validation rules
	 * @return string
	 */
	public function fieldValidationRules();

	/**
	 * Field validation rules
	 * @return array
	 */
	public function fieldValidationMessages();

	/**
	 * Get the machine name for that field
	 * @return string
	 */
	public static function getMachineName();

	/**
	 * Stores the value in database, this value will be serialized.
	 * This is where you do treatment, casting, whatever, before data is stored in db
	 * @param  $value
	 * @return mixed
	 */
	// public function storeValue($value);

	/**
	 * Retrieve the value from database, $value is json decoded and will 
	 * this is where you cast your value
	 * @param  $value
	 * @return mixed
	 */
	// public function retrieveValue($value);

}