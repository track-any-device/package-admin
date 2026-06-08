<?php

namespace TrackAnyDevice\Admin\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use TrackAnyDevice\Core\Enums\StaffDepartment;
use TrackAnyDevice\Core\Models\User;

class AdminUser extends User implements FilamentUser
{
    protected $table = 'users';

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->role?->isCentralStaff()) {
            return false;
        }

        if ($panel->getId() === 'admin') {
            return $this->hasDepartment(StaffDepartment::CoreTeam);
        }

        $department = StaffDepartment::tryFrom($panel->getId());

        if ($department) {
            return $this->hasDepartment($department);
        }

        return false;
    }
}
