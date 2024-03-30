<?php

namespace App\Policies;

use App\Enums\RolesEnum;
use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array(RolesEnum::from($user->role), [RolesEnum::ADMIN, RolesEnum::USER]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Book $model): bool
    {
        return in_array(RolesEnum::from($user->role), [RolesEnum::ADMIN, RolesEnum::USER]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $model): bool
    {
        return in_array(RolesEnum::from($user->role), [RolesEnum::ADMIN, RolesEnum::USER]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Book $model): bool
    {
        return in_array(RolesEnum::from($user->role), [RolesEnum::ADMIN]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Book $model): bool
    {
        return in_array(RolesEnum::from($user->role), [RolesEnum::ADMIN]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Book $model): bool
    {
        return in_array(RolesEnum::from($user->role), [RolesEnum::ADMIN]);
    }
}
