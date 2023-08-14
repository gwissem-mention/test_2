<?php

declare(strict_types=1);

namespace App\Twig;

use App\AppEnum\MultimediaNature;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MultimediaExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('nature_label', [$this, 'getNatureLabel']),
        ];
    }

    public function getNatureLabel(int $natureCode): ?string
    {
        return MultimediaNature::getLabel($natureCode);
    }
}
