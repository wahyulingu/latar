<?php

namespace App\Policies\Content;

use App\Actions\Profile\Author\Check as AuthorCheck;
use App\Models\Content\ContentArticle;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function __construct(protected AuthorCheck $authorCheck)
    {
    }

    public function viewAny(User $user)
    {
        return $this->authorCheck->hasProfile($user)

            || $user->can('index.article')

            ? true : false;
    }

    public function view(User $user, ContentArticle $article)
    {
        return $user->id

            == $article->author->user->id
            || $user->can('view.article')

            ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Content\ContentArticle $author
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->authorCheck->hasProfile($user);
    }

    public function update(User $user, ContentArticle $article)
    {
        return $user->id

            == $article?->author->user->id
            || $user->can('update.article')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ContentArticle $article)
    {
        return $user->id

            == $article->author->user->id
            || $user->can('delete.article')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ContentArticle $article)
    {
        return $user->id

            == $article->author->user->id
            || $user->can('restore.article')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ContentArticle $article)
    {
        return $user->id

            == $article->author->user->id
            || $user->can('forcedelete.article')

            ? true : false;
    }
}
