<?php

namespace App\Policies;

use App\Models\MaintenanceSchedule;
use App\Models\User;

class MaintenanceSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MaintenanceSchedule $maintenanceSchedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $vehicle = $maintenanceSchedule->vehicle;
        if ($user->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) === $user->id)) {
            return true;
        }

        return $user->isMechanic()
            && (($maintenanceSchedule->assigned_mechanic_id ?? $maintenanceSchedule->mechanic_id) === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function update(User $user, MaintenanceSchedule $maintenanceSchedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $vehicle = $maintenanceSchedule->vehicle;

        return $user->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) === $user->id);
    }

    public function delete(User $user, MaintenanceSchedule $maintenanceSchedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $vehicle = $maintenanceSchedule->vehicle;

        return $user->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) === $user->id);
    }

    /**
     * Mechanics may record parts, labor, and completion for assigned jobs only.
     */
    public function fillServiceRecord(User $user, MaintenanceSchedule $maintenanceSchedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isMechanic()
            && (($maintenanceSchedule->assigned_mechanic_id ?? $maintenanceSchedule->mechanic_id) === $user->id);
    }
}
