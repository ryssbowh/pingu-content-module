<?php

namespace Pingu\Content\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;

class CreateContentTypePermissions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * Creates all permissions associated to this content type
     * and gives those permisisons to Admin role (if config autoGivePermsToAdmin is set)
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $contentType = $event->contentType;
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
        $menu = Menu::findByName('admin-menu');
        $item = MenuItem::findByName('admin-menu.content.create');
        $create = Permission::findByName('create '.$pluralName);
        MenuItem::create([
            'name' => $contentType->name,
            'url' => Content::transformAdminUri('create',[$contentType], true),
            'active' => true,
            'deletable' => false,
            'permission_id' => $create->id
        ], $menu, $item);
    }
}
