<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringExtension extends AbstractExtension
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('bool_to_string', [$this, 'boolToString']),
        ];
    }

    public function boolToString(bool $bool): string
    {
        return $this->translator->trans($bool ? 'pel.yes' : 'pel.no');
    }
}
