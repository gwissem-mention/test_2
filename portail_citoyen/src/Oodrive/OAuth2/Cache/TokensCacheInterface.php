<?php

declare(strict_types=1);

namespace App\Oodrive\OAuth2\Cache;

use App\Oodrive\OAuth2\Exception\OAuthException;
use App\Oodrive\OAuth2\GrantType\GrantTypeInterface;
use App\Oodrive\OAuth2\GrantType\Tokens;

interface TokensCacheInterface
{
    /**
     * @throws OAuthException
     */
    public function get(GrantTypeInterface $grant): Tokens;

    public function clear(): void;
}
