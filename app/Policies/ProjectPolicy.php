<?php

namespace App\Policies;

use App\Models\Project\Project;
use App\Models\User\User;
use Illuminate\Support\Facades\Gate;

class ProjectPolicy
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
    public function view(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Gate::allows('company-owner')
            or Gate::allows('project-management','create-project')
            ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return Gate::allows('company-owner')
            or Gate::allows('project-management','update-project')
            and $project->creator->id === $user->id
            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return Gate::allows('company-owner');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
