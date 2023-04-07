<?php

declare(strict_types=1);

namespace App\Oodrive\OAuth2\GrantType;

use App\Oodrive\OAuth2\Exception\OAuthException;

interface GrantTypeInterface
{
    /**
     * Retrieves tokens from an OAuth server.
     *
     * @return Tokens the tokens retrieved from the OAuth server
     *
     * @throws OAuthException when the tokens could not be retrieved
     */
    public function getTokens(): Tokens;

    /**
     * Factory method that builds a refresh grant type corresponding to this grant type.
     *
     * @param string $refreshToken an OAuth refresh token acquired by this grant type
     *
     * @return GrantTypeInterface a refresh grant type built from this grant type
     */
    public function getRefreshTokenGrant(string $refreshToken): GrantTypeInterface;
}
