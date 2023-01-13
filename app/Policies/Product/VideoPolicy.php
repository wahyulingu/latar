<?php

namespace App\Policies\Product;

use App\Models\Product\ProductVideo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
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

    public function view(User $user, ProductVideo $video)
    {
        $product = (bool) $video->product->master

            ? $video->product->master
            : $video->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('view.product.video')

            ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User                  $user
     * @param \App\Models\Product\ProductMaster $owner
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create()
    {
        return true;
    }

    public function update(User $user, ProductVideo $video)
    {
        $product = (bool) $video->product->master

            ? $video->product->master
            : $video->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('update.product.video')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Product\ProductVideo $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductVideo $video)
    {
        $product = (bool) $video->product->master

            ? $video->product->master
            : $video->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('delete.product.video')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Product\ProductVideo $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductVideo $video)
    {
        $product = (bool) $video->product->master

            ? $video->product->master
            : $video->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('restore.product.video')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Product\ProductVideo $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductVideo $video)
    {
        $product = (bool) $video->product->master

            ? $video->product->master
            : $video->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('forcedelete.product.video')

            ? true : false;
    }
}
