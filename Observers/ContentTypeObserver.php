<?php

namespace Pingu\Content\Observers;

use Illuminate\Support\Str;
use Pingu\Content\Bundles\ContentTypeBundle;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Field\Entities\BundleField;
use Pingu\Field\Entities\FieldText;
use Pingu\Field\Entities\FieldTextLong;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\Permissions\Exceptions\PermissionDoesNotExist;
use Pingu\User\Entities\Role;

class ContentTypeObserver
{
    protected $perms = [
        "create ",
        "view any ",
        "edit own ",
        "delete own ",
        "edit any ",
        "delete any "
    ];
    /**
     * Creates permissions, menu items and bundle fields for a new content type
     * 
     * @param ContentType $contentType
     */
    public function created(ContentType $contentType)
    {
        $this->createPermissions($contentType);
        $this->createMenuItem($contentType);
        $this->createDefaultFields($contentType);
    }

    /**
     * Deletes the permissions and menu items associated with a deleted content type
     * 
     * @param ContentType $contentType
     */
    public function deleted(ContentType $contentType)
    {
        $pluralName = Str::plural($contentType->machineName);

        foreach ($this->perms as $perm) {
            try {
                if ($permission = Permission::findByName($perm.$pluralName)) {
                    $permission->delete();
                }
            } catch (PermissionDoesNotExist $e) {}   
        }
        if ($item = MenuItem::findByMachineName('admin-menu.content.create.'.$contentType->machineName)) {
            $item->delete();
        }
    }

    /**
     * Delete all soft deleted content
     * 
     * @param ContentType $contentType
     */
    public function deleting(ContentType $contentType)
    {
        foreach ($contentType->contents()->withTrashed()->get() as $content) {
            $content->forceDelete();
        }
    }

    /**
     * Create permissions associated to a content type.
     * Will give permissions to the role Admin if config 
     * content.autoGivePermsToAdmin is true
     * 
     * @param ContentType $contentType
     */
    protected function createPermissions(ContentType $contentType)
    {
        $pluralName = Str::plural($contentType->machineName);
        $admin = Role::find(4);

        foreach ($this->perms as $perm) {
            $perm = Permission::create(['name' => $perm.$pluralName, 'section' => 'Content']);
            if (config('content.autoGivePermsToAdmin', true)) {
                $admin->givePermissionTo($perm);
            }
        }
    }

    /**
     * Creates a menu item in create section of admin menu
     * if config content.createMenuItem is true
     * 
     * @param ContentType $contentType
     */
    protected function createMenuItem(ContentType $contentType)
    {
        if (!config('content.createMenuItem', true)) {
            return;
        }
        $pluralName = Str::plural($contentType->machineName);
        $create = Permission::findByName('create '.$pluralName);
        $bundle = new ContentTypeBundle($contentType);
        
        MenuItem::create(
            [
            'name' => $contentType->name,
            'url' => Content::uris()->make('create', [$bundle], adminPrefix()),
            'active' => true,
            'deletable' => false,
            'permission_id' => $create->id
            ], 'admin-menu', 'admin-menu.content.create'
        );
    }

    /**
     * Creates default bundle fields for a content
     * 
     * @param ContentType $contentType
     */
    protected function createDefaultFields(ContentType $contentType)
    {
        $bundle = new ContentTypeBundle($contentType);
        $contentField = FieldTextLong::create(
            [
            'default' => '',
            'required' => false
            ]
        );

        BundleField::create(
            [
            'name' => 'Content',
            'machineName' => 'content',
            'cardinality' => 1,
            'deletable' => 1,
            'editable' => 1
            ], $bundle, $contentField
        );
    }
}