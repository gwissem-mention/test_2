<?php

declare(strict_types=1);

namespace App\Referential\Provider;

interface ProviderInterface
{
    /**
     * @return array<string, string>
     */
    public function getChoices(): array;
}
