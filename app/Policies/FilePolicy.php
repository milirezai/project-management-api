<?php

namespace App\Policies;

use App\Models\Collaboration\File;
use App\Models\User\User;
use Illuminate\Support\Facades\Gate;

class FilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return Gate::allows('company-owner');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, File $file): bool
    {
        return Gate::any(['company-owner','project-management','developer'])
            or $file->user->id === $user->id
            ? true : false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Gate::any(['company-owner','project-management','developer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, File $file): bool
    {
        return Gate::any(['company-owner','project-management','developer'])
            or $file->user->id === $user->id
            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, File $file): bool
    {
        return Gate::any(['company-owner','project-management','developer'])
            or $file->user->id === $user->id
            ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, File $file): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, File $file): bool
    {
        return false;
    }
}
