<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isCeo() || $user->isManager() || $user->isDriver() || $user->isAdmin();
    }

    public function view(User $user, Trip $trip): bool
    {
        if ($user->isCeo() || $user->isAdmin()) {
            return true;
        }

        return $user->isDriver() && $trip->driver_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    public function update(User $user, Trip $trip): bool
    {
        // Admin can update anything
        if ($user->isAdmin()) {
            return true;
        }

        // Managers can update trips of their drivers
        return $user->isManager() && $trip->driver->manager_id == $user->id;
    }

    public function delete(User $user, Trip $trip): bool
    {
        // Admin can delete anything
        if ($user->isAdmin()) {
            return true;
        }

        // Managers can delete trips of their drivers
        return $user->isManager() && $trip->driver->manager_id == $user->id;
    }
}
