<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;

class ClassPolicy
{
    /**
     * Determine if the user can view any classes (listing).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the class.
     */
    public function view(User $user, Classroom $class): bool
    {
        return $class->isOwner($user) || $class->isParticipant($user);
    }

    /**
     * Determine if the user can create classes.
     */
    public function create(User $user): bool
    {
        return $user->role === 'lecturer';
    }

    /**
     * Determine if the user can update the class.
     */
    public function update(User $user, Classroom $class): bool
    {
        return $user->role === 'lecturer' && $class->isOwner($user);
    }

    /**
     * Determine if the user can delete the class.
     */
    public function delete(User $user, Classroom $class): bool
    {
        return $user->role === 'lecturer' && $class->isOwner($user);
    }
}