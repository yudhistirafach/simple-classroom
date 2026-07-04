<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(User $user, Classroom $class): bool
    {
        return $class->isOwner($user) || $class->isParticipant($user);
    }

    public function view(User $user, Announcement $announcement): bool
    {
        return $this->viewAny($user, $announcement->class);
    }

    public function create(User $user, Classroom $class): bool
    {
        return $user->role === 'lecturer' && $class->isOwner($user);
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->role === 'lecturer' && $announcement->class->isOwner($user);
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->role === 'lecturer' && $announcement->class->isOwner($user);
    }
}