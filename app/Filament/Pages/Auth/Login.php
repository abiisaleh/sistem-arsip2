<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as AuthLogin;

class Login extends AuthLogin
{
    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string
    {
        return '';
    }

    protected static string $view = 'filament.pages.auth.login';
}
