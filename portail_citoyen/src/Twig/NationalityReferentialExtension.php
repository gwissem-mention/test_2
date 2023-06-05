<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Provider\Nationality\CachedNationalityProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NationalityReferentialExtension extends AbstractExtension
{
    public function __construct(protected readonly CachedNationalityProvider $nationalityProvider)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('nationality_referential_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(string $code): string
    {
        return $this->nationalityProvider->getByCode($code)->getLabel();
    }
}
