<?php

namespace App\Policies;

use App\Models\Project\Task;
use App\Models\User\User;
use Illuminate\Support\Facades\Gate;
use function Laravel\Prompts\table;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return Gate::any(['company-owner','project-management','developer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return Gate::any(['company-owner','project-management','developer'])
            or $user->id === $task->creator->id
            or $user->id === $task->assignee->id
            ? true : false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Gate::any(['company-owner','project-management']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return Gate::any(['company-owner','project-management'])
            or $user->id === $task->creator->id
            ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        eturn Gate::any(['company-owner','project-management']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
