<?php

namespace App\Policies\Product;

use App\Models\Product\ProductSpecification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecificationPolicy
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

    public function view(User $user, ProductSpecification $specification)
    {
        $product = (bool) $specification->product->master

            ? $specification->product->master
            : $specification->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('view.product.specification')

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

    public function update(User $user, ProductSpecification $specification)
    {
        $product = (bool) $specification->product->master

            ? $specification->product->master
            : $specification->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('update.product.specification')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Product\ProductSpecification $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductSpecification $specification)
    {
        $product = (bool) $specification->product->master

            ? $specification->product->master
            : $specification->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('delete.product.specification')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Product\ProductSpecification $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductSpecification $specification)
    {
        $product = (bool) $specification->product->master

            ? $specification->product->master
            : $specification->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('restore.product.specification')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Product\ProductSpecification $product
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductSpecification $specification)
    {
        $product = (bool) $specification->product->master

            ? $specification->product->master
            : $specification->product;

        return $user->id

            == $product->owner->user->id
            || $user->can('forcedelete.product.specification')

            ? true : false;
    }
}
