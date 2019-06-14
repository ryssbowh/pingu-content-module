<?php

namespace Pingu\Content\Forms;

use Pingu\Content\Entities\Content;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\Link;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Form;

class EditContentForm extends Form
{
	public $content;

	/**
	 * Bring variables in your form through the constructor :
	 */
	public function __construct(Content $content)
	{
		$this->content = $content;
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
                	'default' => $this->content->title,
                	'label' => $this->content->content_type->titleField
                ]
            ],
            'published' => [
	            'field' => Checkbox::class,
	            'options' => [
	            	'default' => $this->content->published
	            ]
	        ],
	        'submit' => [
	        	'field' => Submit::class,
	        ],
	        'link' => [
	        	'field' => Link::class,
	        	'options' => [
	        		'label' => 'Back',
	        		'url' => url()->previous(),
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
		return 'PUT';
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
		return ['url' => Content::transformAdminUri('update', [$this->content], true)];
	}

	/**
	 * Name for this form, ideally it would be application unique, 
	 * best to prefix it with the name of the module it's for.
	 * 
	 * @return string
	 */
	public function name()
	{
		return 'content-edit-content';
	}
}