<?php

namespace App\Policies\Product;

use App\Models\Product\ProductVariant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VariantPolicy
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

    public function view(User $user, ProductVariant $variant)
    {
        return $user->id

            == $variant->master->owner->user->id
            || $user->can('view.product.variant')

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

    public function update(User $user, ProductVariant $variant)
    {
        return $user->id

            == $variant->master->owner->user->id
            || $user->can('update.product.variant')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Product\ProductVariant $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductVariant $variant)
    {
        return $user->id

            == $variant->master->owner->user->id
            || $user->can('delete.product.variant')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Product\ProductVariant $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductVariant $variant)
    {
        return $user->id

            == $variant->master->owner->user->id
            || $user->can('restore.product.variant')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Product\ProductVariant $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductVariant $variant)
    {
        return $user->id

            == $variant->master->owner->user->id
            || $user->can('forcedelete.product.variant')

            ? true : false;
    }
}
