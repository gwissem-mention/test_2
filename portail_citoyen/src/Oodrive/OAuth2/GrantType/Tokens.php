<?php

declare(strict_types=1);

namespace App\Oodrive\OAuth2\GrantType;

class Tokens
{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $refreshToken
    ) {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
