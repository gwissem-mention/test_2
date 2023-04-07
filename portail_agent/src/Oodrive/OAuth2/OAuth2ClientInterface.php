<?php

namespace App\Oodrive\OAuth2;

use App\Oodrive\OAuth2\Cache\TokensCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface OAuth2ClientInterface extends HttpClientInterface
{
    public function setCache(TokensCacheInterface $cache): self;
}
