<?php

namespace Pingu\Content\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\User\Entities\User;

class ContentPolicy
{
    use HandlesAuthorization;

    /**
     * View access
     * @param  User    $user
     * @param  Content $content
     * @return bool
     */
    public function view(User $user, Content $content)
    {
        if($user == $content->creator){
            return true;
        }
        $perm = $this->permName('view any', $content);
        return $user->hasPermissionTo($perm);
    }

    /**
     * Edit access
     * @param  User    $user
     * @param  Content $content
     * @return bool
     */
    public function edit(User $user, Content $content)
    {   
        $any = $this->permName('edit any', $content);
        $own = $this->permName('edit own', $content);

        if($user == $content->creator){
            return $user->hasAnyPermission([$own, $any]);
        }
        else{
            return $user->hasPermissionTo($any);   
        }
    }

    /**
     * Delete access
     * @param  User    $user
     * @param  Content $content
     * @return bool
     */
    public function delete(User $user, Content $content)
    {
        $any = $this->permName('delete any', $content);
        $own = $this->permName('delete own', $content);

        if($user == $content->creator){
            return $user->hasAnyPermission([$own, $any]);
        }
        else{
            return $user->hasPermissionTo($any);   
        }
    }

    /**
     * Generate a permission name from content and action
     * @param  string  $action
     * @param  Content $content
     * @return string
     */
    protected function permName(string $action, Content $content)
    {
        return trim($action).' '.Str::plural($content->content_type->machineName);
    }
}
