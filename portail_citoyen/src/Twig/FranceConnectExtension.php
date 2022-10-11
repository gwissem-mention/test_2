<?php

declare(strict_types=1);

namespace App\Twig;

use App\FranceConnect\IdentitySessionHandler;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FranceConnectExtension extends AbstractExtension
{
    public function __construct(
        private readonly IdentitySessionHandler $fcIdentitySessionHandler
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_france_connected', [$this, 'isFranceConnected']),
        ];
    }

    public function isFranceConnected(): bool
    {
        return $this->fcIdentitySessionHandler->isConnected();
    }
}
