<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine if the user can view any tasks in a class.
     */
    public function viewAny(User $user, Classroom $class): bool
    {
        return $class->isOwner($user) || $class->isParticipant($user);
    }

    /**
     * Determine if the user can view a specific task.
     */
    public function view(User $user, Task $task): bool
    {
        return $this->viewAny($user, $task->class);
    }

    /**
     * Determine if the user can create a task in a class.
     */
    public function create(User $user, Classroom $class): bool
    {
        return $user->role === 'lecturer' && $class->isOwner($user);
    }

    /**
     * Determine if the user can update a task.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->role === 'lecturer' && $task->class->isOwner($user);
    }

    /**
     * Determine if the user can delete a task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->role === 'lecturer' && $task->class->isOwner($user);
    }
}