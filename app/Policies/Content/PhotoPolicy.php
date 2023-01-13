<?php

namespace App\Policies\Content;

use App\Models\Content\ContentPhoto;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny()
    {
        return true;
    }

    public function view(User $user, ContentPhoto $photo)
    {
        $content = (bool) $photo->content->master

            ? $photo->content->master
            : $photo->content;

        return $user->id

            == $content->author->user->id
            || $user->can('view.content.photo')

            ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User                  $user
     * @param \App\Models\Product\ProductMaster $author
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create()
    {
        return true;
    }

    public function update(User $user, ContentPhoto $photo)
    {
        $content = (bool) $photo->content->master

            ? $photo->content->master
            : $photo->content;

        return $user->id

            == $content->author->user->id
            || $user->can('update.content.photo')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Product\ContentPhoto $content
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ContentPhoto $photo)
    {
        $content = (bool) $photo->content->master

            ? $photo->content->master
            : $photo->content;

        return $user->id

            == $content->author->user->id
            || $user->can('delete.content.photo')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Product\ContentPhoto $content
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ContentPhoto $photo)
    {
        $content = (bool) $photo->content->master

            ? $photo->content->master
            : $photo->content;

        return $user->id

            == $content->author->user->id
            || $user->can('restore.content.photo')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Product\ContentPhoto $content
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ContentPhoto $photo)
    {
        $content = (bool) $photo->content->master

            ? $photo->content->master
            : $photo->content;

        return $user->id

            == $content->author->user->id
            || $user->can('forcedelete.content.photo')

            ? true : false;
    }
}
