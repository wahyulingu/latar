<?php

namespace App\Policies\Content;

use App\Actions\Profile\Author\Check as AuthorCheck;
use App\Models\Content\ContentPage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function __construct(protected AuthorCheck $authorCheck)
    {
    }

    public function viewAny(User $user)
    {
        return $this->authorCheck->hasProfile($user)

            || $user->can('index.page')

            ? true : false;
    }

    public function view(User $user, ContentPage $page)
    {
        return $user->id

            == $page->author->user->id
            || $user->can('view.page')

            ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Content\ContentPage $author
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->authorCheck->hasProfile($user);
    }

    public function update(User $user, ContentPage $page)
    {
        return $user->id

            == $page?->author->user->id
            || $user->can('update.page')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ContentPage $page)
    {
        return $user->id

            == $page->author->user->id
            || $user->can('delete.page')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ContentPage $page)
    {
        return $user->id

            == $page->author->user->id
            || $user->can('restore.page')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ContentPage $page)
    {
        return $user->id

            == $page->author->user->id
            || $user->can('forcedelete.page')

            ? true : false;
    }
}
