<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NewWindowExtension extends AbstractExtension
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('add_new_window_text', [$this, 'addNewWindowText']),
        ];
    }

    public function addNewWindowText(string $title): string
    {
        return sprintf('%s (%s)', $title, $this->translator->trans('pel.new.window'));
    }
}
