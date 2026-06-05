<?php

namespace TrackAnyDevice\Admin\Concerns;

use TrackAnyDevice\Core\Enums\StaffDepartment;

trait HasDepartmentAccess
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user || ! method_exists($user, 'hasDepartment')) {
            return false;
        }

        if ($user->isAdmin() || $user->hasDepartment(StaffDepartment::CoreTeam)) {
            return true;
        }

        foreach (static::getAllowedDepartments() as $dept) {
            if ($user->hasDepartment($dept)) {
                return true;
            }
        }

        return false;
    }

    /** @return StaffDepartment[] */
    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }
}
