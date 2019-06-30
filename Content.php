<?php

namespace Pingu\Content;

use Illuminate\Contracts\Auth\Access\Gate;
use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Entities\Content as ContentModel;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Field;
use Pingu\Content\Entities\FieldValue;
use Pingu\Content\Events\ContentCreated;
use Pingu\Content\Exceptions\ContentContractMissing;
use Pingu\Content\Exceptions\ContentFieldAlreadyExists;
use Pingu\Content\Forms\AddContentForm;
use Pingu\Content\Forms\EditContentForm;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Exceptions\ModelNotFormable;
use Pingu\Forms\Support\Field as FormField;
use Pingu\Forms\Support\Fields\Submit;

class Content
{
	protected $contentFields = [];

	/**
	 * Registers a type of content field
	 * @param  string $name
	 * @param  string $class
	 * @throws ContentFieldAlreadyExists
	 */
	public function registerContentField(string $class)
	{
		$name = $class::getMachineName();
		if(isset($contentFields[$name])){
			throw ContentFieldAlreadyExists::create($name);
		}
		if(in_array($class, $this->contentFields)){
			throw ContentFieldAlreadyExists::create($class);
		}
		$impl = class_implements($class);
		if(!isset($impl[ContentFieldContract::class])){
			throw ContentContractMissing::create($class);
		}
		$this->contentFields[$name] = $class;
	}

	/**
	 * Registers multiple content fields
	 * @param  array  $fields
	 */
	public function registerContentFields(array $fields)
	{
		foreach($fields as $class){
			$this->registerContentField($class);
		}
	}

	/**
	 * Get all registered content fields
	 * @return array
	 */
	public function getRegisteredContentFields()
	{
		return $this->contentFields;
	}

	/**
	 * Get a registered content field class name
	 * @param  string $name
	 * @return string
	 * @throws  ContentFieldNotFound
	 * @throws  ModelNotFormable
	 */
	public function getRegisteredContentField(string $name)
	{
		if(!isset($this->contentFields[$name])){
			throw ContentFieldNotRegistered::create($name);
		}
		$contentField = new $this->contentFields[$name];
		if(!$contentField instanceof FormableContract){
            throw new ModelNotFormable($contentField);
        }
		return $contentField;
	}

	/**
	 * Creates a new content
	 * @param  ContentType $type
	 * @param  array       $values
	 * @return Content
	 */
	public function createContent(ContentType $type, array $values)
	{
		$content = new ContentModel([
            'title' => $values['title'],
            'published' => $values['published']
        ]);
        $content->content_type()->associate($type);
        $content->creator()->associate(\Auth::user());
        $content->save();
        foreach($type->fields as $field){
        	$this->createFieldValue($field, $content, $values[$field->machineName]);
        }
        return $content;
	}

	/**
	 * Updates a content
	 * @param  Content $content
	 * @param  array   $values
	 * @return Content
	 */
	public function updateContent(ContentModel $content, array $values)
	{
		$content->title = $values['title'];
		$content->published = $values['published'];
        foreach($content->values as $value){
        	$data = $value->field->instance->storeValue($values[$value->field->machineName]);
        	$value->value = $data;
        	$value->save();
        }
        $content->save();
        return $content;
	}

	/**
	 * Creates a field value for a field on a content
	 * @param  Field   $field
	 * @param  Content $content
	 * @param  mixed  $value
	 * @return FieldValue
	 */
	public function createFieldValue(Field $field, ContentModel $content, $value)
	{
		$fieldValue = new FieldValue([
    		'value' => $field->instance->storeValue($value)
    	]);
    	$fieldValue->field()->associate($field);
    	$fieldValue->content()->associate($content);
    	$fieldValue->save();
    	return $fieldValue;
	}

	/**
	 * Creates a form to create a request
	 * @param  ContentType $type
	 * @return Form
	 */
	public function getCreateContentForm(ContentType $type)
	{
		$form = new AddContentForm($type);

        foreach($type->fields as $field){
            $definition = $field->buildFieldDefinition();
            $definition = array_replace_recursive($definition, $field->instance->fieldDefinition());
            $fieldClass = FormField::buildFieldClass($field->machineName, $definition);
            $form->addField($field->machineName, $fieldClass);
        }

        $form->moveFieldDown('published')
        	->moveFieldDown('submit');
        
        return $form;
	}

	/**
	 * Creates a form to edit a content
	 * @param  Content $content
	 * @return Form
	 */
	public function getEditContentForm(ContentModel $content)
	{
		$form = new EditContentForm($content);

        foreach($content->values as $value){
            $field = $value->field;
            $definition = $field->buildFieldDefinition();
            $definition = array_replace_recursive($definition, $field->instance->fieldDefinition());
            $definition['options']['default'] = $value->value;
            $fieldClass = FormField::buildFieldClass($field->machineName, $definition);
            $form->addField($field->machineName, $fieldClass);
        }

        $form->moveFieldDown('published')
        	->moveFieldDown('submit')
        	->moveFieldDown('link');
        
        return $form;
	}
}