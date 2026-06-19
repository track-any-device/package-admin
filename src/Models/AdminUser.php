<?php

namespace TrackAnyDevice\Admin\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use TrackAnyDevice\Core\Models\User;

class AdminUser extends User implements FilamentUser
{
    protected $table = 'users';

    /** Single-Role model (Workstream F): Admin + Core may access the admin panel; deletes gated to Admin. */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->role?->canAccessFilament();
    }
}
