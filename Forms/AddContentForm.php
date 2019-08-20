<?php

namespace Pingu\Content\Forms;

use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\Link;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Form;

class AddContentForm extends Form
{
	public $type;
	/**
	 * Bring variables in your form through the constructor :
	 */
	public function __construct(ContentType $type)
	{
		$this->type = $type;
		parent::__construct();
	}

	/**
	 * Fields definitions for this form, classes used here
	 * must extend Pingu\Forms\Support\Field
	 * 
	 * @return array
	 */
	public function fields()
	{
		return [
			'title' => [
                'field' => TextInput::class,
                'attributes' => [
                	'required' => true
                ],
                'options' => [
                	'label' => $this->type->titleField
                ]
            ],
            'published' => [
	            'field' => Checkbox::class
	        ],
	        '_submit' => [
	        	'field' => Submit::class,
	        ],
	        '_back' => [
	        	'field' => Link::class,
	        	'options' => [
	        		'label' => 'Back',
	        		'url' => url()->previous()
	        	],
	        	'attributes' => [
	        		'class' => 'back'
	        	]
	        ]
        ];
	}

	/**
	 * Method for this form, POST GET DELETE PATCH and PUT are valid
	 * 
	 * @return string
	 */
	public function method()
	{
		return 'POST';
	}

	/**
	 * Url for this form, valid values are
	 * ['url' => '/foo.bar']
	 * ['route' => 'login']
	 * ['action' => 'MyController@action']
	 * 
	 * @return array
	 * @see https://github.com/LaravelCollective/docs/blob/5.6/html.md
	 */
	public function url()
	{
		return ['url' => Content::makeUri('store', [$this->type], adminPrefix())];
	}

	/**
	 * Name for this form, ideally it would be application unique, 
	 * best to prefix it with the name of the module it's for.
	 * 
	 * @return string
	 */
	public function name()
	{
		return 'content-create-content';
	}

}