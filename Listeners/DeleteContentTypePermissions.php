<?php

namespace Pingu\Content\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;

class DeleteContentTypePermissions
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
     * Delete all associated permissions and menu item
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
