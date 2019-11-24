<?php 

namespace Pingu\Content\Config;

use Pingu\Core\Settings\SettingsRepository;
use Pingu\Field\BaseFields\Boolean;

class ContentSettings extends SettingsRepository
{
    protected $casts = [
        'content.autoGivePermsToAdmin' => 'bool',
        'content.createMenuItem' => 'bool'
    ];
    protected $accessPermission = 'view content settings';
    protected $editPermission = 'view content settings';
    protected $titles = [
        'content.autoGivePermsToAdmin' => 'Create content type permissions',
        'content.createMenuItem' => 'Create content type menu item'
    ];
    protected $keys = ['content.autoGivePermsToAdmin', 'content.createMenuItem'];
    protected $validations = [
        'content_autoGivePermsToAdmin' => 'boolean',
        'content_createMenuItem' => 'boolean'
    ];
    protected $helpers = [
        'content.autoGivePermsToAdmin' => 'Give all permissions to Admin when creating a content type',
        'content.createMenuItem' => 'Create a menu item under Content->Create when creating a content type'
    ];

    /**
     * @inheritDoc
     */
    public function section(): string
    {
        return 'Content';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'content';
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            new Boolean(
                'content.autoGivePermsToAdmin',
                [
                    'label' => $this->getFieldLabel('content.autoGivePermsToAdmin'),
                    'helper' => $this->helper('content.autoGivePermsToAdmin')
                ]
            ), 
            new Boolean(
                'content.createMenuItem',
                [
                    'label' => $this->getFieldLabel('content.createMenuItem'),
                    'helper' => $this->helper('content.createMenuItem')
                ]
            )
        ];
    }
}