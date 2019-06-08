<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Traits\ContentField;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Fields\Boolean;
use Pingu\Forms\Fields\Email;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Traits\Formable;

class FieldEmail extends BaseModel implements ContentFieldContract
{
	use ContentField, Formable;

    protected $fillable = ['default', 'required'];

    protected $casts = [
        'required' => 'boolean'
    ];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['default', 'required'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['default', 'required'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'default' => [
                'type' => Email::class
            ],
            'required' => [
                'type' => Boolean::class
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'default' => 'email',
            'required' => 'boolean'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'title.required' => 'Title is required'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function friendlyName()
    {
    	return 'Email';
    }

    /**
     * @inheritDoc
     */
    public function fieldType()
    {
        return Email::class;
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinition()
    {
        return [
            'default' => $this->default
        ];
    }

    /**
     * @inheritDoc
     */
    public function fieldValidationRules()
    {
        return ($this->required ? 'required|' : '') . 'email';
    }

    /**
     * @inheritDoc
     */
    public function fieldValidationMessages()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getMachineName()
    {
        return 'email';
    }
}
