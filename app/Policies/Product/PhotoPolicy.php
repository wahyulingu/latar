<?php

namespace App\Policies\Product;

use App\Models\Product\ProductPhoto;
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

    public function view(User $user, ProductPhoto $photo)
    {
        $product = (bool) $photo->product->master

            ? $photo->product->master
            : $photo->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('view.product.photo')

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

    public function update(User $user, ProductPhoto $photo)
    {
        $product = (bool) $photo->product->master

            ? $photo->product->master
            : $photo->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('update.product.photo')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Product\ProductPhoto $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductPhoto $photo)
    {
        $product = (bool) $photo->product->master

            ? $photo->product->master
            : $photo->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('delete.product.photo')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Product\ProductPhoto $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductPhoto $photo)
    {
        $product = (bool) $photo->product->master

            ? $photo->product->master
            : $photo->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('restore.product.photo')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Product\ProductPhoto $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductPhoto $photo)
    {
        $product = (bool) $photo->product->master

            ? $photo->product->master
            : $photo->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('forcedelete.product.photo')

            ? true : false;
    }
}
