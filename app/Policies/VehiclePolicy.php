<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function create(User $user): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin() || ($user->isManager() && $vehicle->manager_id == $user->id);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin() || ($user->isManager() && $vehicle->manager_id == $user->id);
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isManager()) return $vehicle->manager_id == $user->id;
        if ($user->isDriver()) return $vehicle->trips()->where('driver_id', $user->id)->exists();
        return false;
    }
}
