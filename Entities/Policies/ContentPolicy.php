<?php

namespace Pingu\Content\Entities\Policies;

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Entities\Entity;
use Pingu\Entity\Support\BaseEntityPolicy;
use Pingu\User\Entities\User;

class ContentPolicy extends BaseEntityPolicy
{
    /**
     * Create access
     *
     * @param  User $user
     * @return bool
     */
    public function create(?User $user, ?BundleContract $bundle = null)
    {
        $perm = 'create '.Str::plural($bundle->getEntity()->machineName);
        return $user->hasPermissionTo($perm);
    }

    /**
     * Index access
     *
     * @param  User    $user
     * @param  Entity $content
     * @return bool
     */
    public function index(?User $user)
    {
        return $user->hasPermissionTo('view content');
    }

    /**
     * View access
     *
     * @param  User    $user
     * @param  Entity $content
     * @return bool
     */
    public function view(?User $user, Entity $content)
    {
        if($user == $content->creator) {
            return true;
        }
        $perm = $this->permName('view any', $content);
        return $user->hasPermissionTo($perm);
    }

    /**
     * Edit access
     *
     * @param  User    $user
     * @param  Entity $content
     * @return bool
     */
    public function edit(?User $user, Entity $content)
    {   
        $any = $this->permName('edit any', $content);
        $own = $this->permName('edit own', $content);

        if($user == $content->creator) {
            return $user->hasAnyPermission([$own, $any]);
        }
        else{
            return $user->hasPermissionTo($any);   
        }
    }

    /**
     * Delete access
     *
     * @param  User    $user
     * @param  Content $content
     * @return bool
     */
    public function delete(?User $user, Entity $content)
    {
        $any = $this->permName('delete any', $content);
        $own = $this->permName('delete own', $content);

        if($user == $content->creator) {
            return $user->hasAnyPermission([$own, $any]);
        }
        else{
            return $user->hasPermissionTo($any);   
        }
    }

    /**
     * Generate a permission name from content and action
     *
     * @param  string  $action
     * @param  Content $content
     * @return string
     */
    protected function permName(string $action, Entity $content)
    {
        return trim($action).' '.Str::plural($content->content_type->machineName);
    }
}
