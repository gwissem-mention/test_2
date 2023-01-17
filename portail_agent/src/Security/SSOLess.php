<?php

namespace App\Security;

interface SSOLess
{
    public const COOKIE_NAME = 'emulate_sso_with_utilisateur';
    public const START_SESSION_ROUTE = 'app_sso_less_start_session';
}
