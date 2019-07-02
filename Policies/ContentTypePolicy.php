<?php

namespace Pingu\Content\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;
use Pingu\Content\Entities\ContentType;
use Pingu\User\Entities\User;

class ContentTypePolicy
{
    use HandlesAuthorization;

    /**
     * Create content ability per content type
     * @param  User        $user
     * @param  ContentType $type
     * @return bool
     */
    public function create(User $user, ContentType $type)
    {
        $perm = 'create '.Str::plural($type->machineName);
        return $user->hasPermissionTo($perm);
    }
}
