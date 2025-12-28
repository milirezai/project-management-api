<?php

namespace App\Policies;

use App\Models\Collaboration\Company;
use App\Models\User\User;
use Illuminate\Support\Facades\Gate;

class CompanyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        // user -> create company -> set role -> company-woner -> for user
        return Gate::allows('company-owner')
            and $company->owner->id === $user->id
            ? true : false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->company()->count() > 1
            ? false : true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return Gate::allows('company-owner')
            and $company->owner->id === $user->id
                ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        return Gate::allows('company-owner')
            and $company->owner->id === $user->id
                ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return true;
    }
}
