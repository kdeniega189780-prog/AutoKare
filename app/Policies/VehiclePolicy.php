<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) === $user->id)) {
            return true;
        }

        if ($user->isMechanic()) {
            return $vehicle->maintenanceSchedules()
                ->where(fn ($q) => $q->where('assigned_mechanic_id', $user->id)->orWhere('mechanic_id', $user->id))
                ->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) === $user->id);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) === $user->id);
    }
}
