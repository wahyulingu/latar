<?php

namespace App\Policies\Content;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('index.content.category') ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Content\ContentCategory $contentCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $user->can('view.content.category') ? true : false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create.content.category') ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Content\ContentCategory $contentCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->can('update.content.category') ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Content\ContentCategory $contentCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->can('delete.content.category') ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Content\ContentCategory $contentCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
        return $user->can('restore.content.category') ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Content\ContentCategory $contentCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
        return $user->can('forcedelete.content.category') ? true : false;
    }
}
