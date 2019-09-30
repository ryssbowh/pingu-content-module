<?php

namespace Pingu\Content\Observers;

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Entities\Fields\FieldBoolean;
use Pingu\Entity\Entities\Fields\FieldText;
use Pingu\Entity\Entities\Fields\FieldTextLong;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;

class ContentTypeObserver
{
    /**
     * Creates permissions, menu items and bundle fields for a new content type
     * 
     * @param  ContentType  $contentType
     */
    public function created(ContentType $contentType)
    {
        $pluralName = Str::plural($contentType->machineName);
        $perms = [
            "create ",
            "view any ",
            "edit own ",
            "delete own ",
            "edit any ",
            "delete any "
        ];
        $admin = Role::find(4);
        foreach($perms as $perm){
            $perm = Permission::create(['name' => $perm.$pluralName, 'section' => 'Content']);
            if(config('content.content_types.autoGivePermsToAdmin')){
                $admin->givePermissionTo($perm);
            }
        }
        $menu = Menu::findByMachineName('admin-menu');
        $item = MenuItem::findByMachineName('admin-menu.content.create');
        $create = Permission::findByName('create '.$pluralName);
        // MenuItem::create([
        //     'name' => $contentType->name,
        //     'url' => Content::makeUri('create',[$contentType], adminPrefix()),
        //     'active' => true,
        //     'deletable' => false,
        //     'permission_id' => $create->id
        // ], $menu, $item);

        // FieldTextLong::create([
        //     'machineName' => 'content',
        //     'deletable' => true
        // ],[
        //     'helper' => 'The content content',
        //     'name' => 'Content',
        //     'required' => false,
        //     'default' => ''
        // ], $contentType);
    }

    /**
     * Deletes the permissions and menu items associated with a deleted content type
     * 
     * @param  ContentType $contentType
     */
    public function deleted(ContentType $contentType)
    {
        $pluralName = Str::plural($contentType->machineName);
        $perms = [
            "create ",
            "view any ",
            "edit own ",
            "delete own ",
            "edit any ",
            "delete any "
        ];
        foreach($perms as $perm){
            if($permission = Permission::findByName($perm.$pluralName)){
                $permission->delete();
            }
        }
        if($item = MenuItem::findByMachineName('admin-menu.content.create.'.$contentType->machineName)){
            $item->delete();
        }
    }
}