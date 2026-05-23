<?php

namespace TrackAnyDevice\Admin\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use TrackAnyDevice\Core\Models\User;

class AdminUser extends User implements FilamentUser
{
    protected $table = 'users';

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role?->isCentralStaff() ?? false;
    }
}
