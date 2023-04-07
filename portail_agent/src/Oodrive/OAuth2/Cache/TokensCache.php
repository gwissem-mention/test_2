<?php

declare(strict_types=1);

namespace App\Oodrive\OAuth2\Cache;

use App\Oodrive\OAuth2\GrantType\GrantTypeInterface;
use App\Oodrive\OAuth2\GrantType\Tokens;

class TokensCache implements TokensCacheInterface
{
    private ?Tokens $tokens = null;

    public function get(GrantTypeInterface $grant): Tokens
    {
        if (null === $this->tokens) {
            $this->tokens = $grant->getTokens();
        }

        return $this->tokens;
    }

    public function clear(): void
    {
        $this->tokens = null;
    }
}
