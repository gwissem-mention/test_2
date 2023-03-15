<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreRequestAccessToken
{
    public const NAME = 'oodrive.oauth.pre_request_access_token';

    public function __construct(
        private readonly string $grantType,
        private readonly string $workspace,
    ) {
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function getWorkspace(): string
    {
        return $this->workspace;
    }
}
