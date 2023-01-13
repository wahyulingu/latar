<?php

namespace App\Policies\Product;

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
        return $user->can('index.product.category') ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Product\ProductCategory $productCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $user->can('view.product.category') ? true : false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create.product.category') ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Product\ProductCategory $productCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->can('update.product.category') ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Product\ProductCategory $productCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->can('delete.product.category') ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Product\ProductCategory $productCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
        return $user->can('restore.product.category') ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Product\ProductCategory $productCategory
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
        return $user->can('forcedelete.product.category') ? true : false;
    }
}
