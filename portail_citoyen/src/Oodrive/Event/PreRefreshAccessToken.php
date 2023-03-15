<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreRefreshAccessToken
{
    public const NAME = 'oodrive.oauth.pre_refresh_access_token';

    public function __construct(
        private readonly string $workspace,
    ) {
    }

    public function getWorkspace(): string
    {
        return $this->workspace;
    }
}
