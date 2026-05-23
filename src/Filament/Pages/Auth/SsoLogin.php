<?php

namespace TrackAnyDevice\Admin\Filament\Pages\Auth;

use Filament\Pages\SimplePage;

/**
 * Bare Filament page that immediately redirects to the SSO server.
 * Registered as the panel's login page so Filament routes unauthenticated
 * users here instead of showing its own login form.
 */
class SsoLogin extends SimplePage
{
    public function mount(): void
    {
        if (auth()->check()) {
            $this->redirect(filament()->getPanel()->getUrl(), navigate: false);
            return;
        }

        $this->redirect(route('admin.sso.redirect'), navigate: false);
    }

    public function getView(): string
    {
        return 'filament::pages.auth.login';
    }
}
