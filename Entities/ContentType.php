<?php

namespace Pingu\Content\Entities;

use Pingu\Content\Entities\Content;
use Pingu\Content\Routes\Entities\ContentTypeRoutes;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Contracts\EntityContract;
use Pingu\Entity\Contracts\Routes;
use Pingu\Entity\Entities\BaseEntity;
use Pingu\Entity\Traits\EntityBundle;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Text;
use Pingu\Jsgrid\Fields\Text as JsGridText;

class ContentType extends BaseEntity implements BundleContract
{
	use HasMachineName, EntityBundle;

    protected $fillable = ['name', 'machineName','description'];

    protected $visible = ['id', 'name', 'machineName','description'];

    protected $observables = ['bundleCreated'];

    public $adminListFields = ['name', 'description'];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['name', 'machineName','description'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['name','description'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'name' => [
                'field' => TextInput::class,
                'options' => [
                    'type' => Text::class
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            'description' => [
                'field' => TextInput::class,
                'options' => [
                    'type' => Text::class
                ],
            ],
            'machineName' => [
                'field' => TextInput::class,
                'attributes' => [
                    'class' => 'js-dashify',
                    'data-dashifyfrom' => 'name',
                    'required' => true,
                    'type' => Text::class
                ]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'name' => 'required|string',
            'description' => 'sometimes|string',
            'machineName' => 'required|unique:content_types,machineName,'.$this->id.',id'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'machineName.required' => 'Machine Name is required',
            'machineName.unique' => 'Machine name already exists'
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'machineName';
    }

    /**
     * @inheritDoc
     */
    public function jsGridFields()
    {
    	return [
    		'name' => [
    			'type' => JsGridText::class
    		],
            'description' => [
                'type' => JsGridText::class
            ]
    	];
    }

    public function routes(): Routes
    {
        return new ContentTypeRoutes($this);
    }

    /**
     * Get all content associated to this content type
     * 
     * @return Relation
     */
    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function bundleFriendlyName(): string
    {
        return $this->name;
    }

    public function bundleName(): string
    {
        return class_basename(get_class($this)).'.'.$this->machineName;
    }

    public function createEntity(array $values): EntityContract
    {
        $content = new Content();
        $content->content_type()->associate($this);
        $content->creator()->associate(\Auth::user());
        $content->slug = $content->generateSlug(Str::slug($values['title']));
        $content->save();
        $content->saveFieldValues($values);
        return $content;
    }
}
