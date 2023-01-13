<?php

namespace App\Policies\Product;

use App\Actions\Profile\Owner\Check as OwnerCheck;
use App\Models\Product\ProductMaster;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MasterPolicy
{
    use HandlesAuthorization;

    public function __construct(protected OwnerCheck $ownerCheck)
    {
    }

    public function viewAny(User $user)
    {
        return $this->ownerCheck->hasProfile($user)

            || $user->can('index.product')

            ? true : false;
    }

    public function view(User $user, ProductMaster $product)
    {
        return $user->id

            == $product->owner->user->id
            || $user->can('view.product')

            ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Product\ProductMaster $owner
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->ownerCheck->hasProfile($user);
    }

    public function update(User $user, ProductMaster $product)
    {
        return $user->id

            == $product?->owner->user->id
            || $user->can('update.product')

            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductMaster $product)
    {
        return $user->id

            == $product->owner->user->id
            || $user->can('delete.product')

            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductMaster $product)
    {
        return $user->id

            == $product->owner->user->id
            || $user->can('restore.product')

            ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductMaster $product)
    {
        return $user->id

            == $product->owner->user->id
            || $user->can('forcedelete.product')

            ? true : false;
    }
}
