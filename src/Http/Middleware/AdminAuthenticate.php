<?php

namespace TrackAnyDevice\Admin\Http\Middleware;

use Filament\Http\Middleware\Authenticate;

/**
 * Overrides Filament's Authenticate middleware to redirect unauthenticated
 * admin users to the SSO server instead of a Filament login form.
 */
class AdminAuthenticate extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        return route('admin.sso.redirect');
    }
}
