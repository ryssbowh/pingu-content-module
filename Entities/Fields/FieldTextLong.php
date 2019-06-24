<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Traits\ContentField;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\Textarea;
use Pingu\Forms\Support\Types\Boolean;
use Pingu\Forms\Support\Types\Text;
use Pingu\Forms\Traits\Models\Formable;

class FieldTextLong extends BaseModel implements ContentFieldContract
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
                'field' => Textarea::class
            ],
            'required' => [
                'field' => Checkbox::class
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'default' => 'string',
            'required' => 'boolean'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function friendlyName()
    {
    	return 'Text (long)';
    }

    /**
     * @inheritDoc
     */
    public function fieldType()
    {
        return Textarea::class;
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinition()
    {
        return [
            'field' => Textarea::class,
            'options' => [
                'default' => $this->default
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function fieldValidationRules()
    {
        return ($this->required ? 'required|' : '') . 'string';
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
        return 'text_long';
    }
}
