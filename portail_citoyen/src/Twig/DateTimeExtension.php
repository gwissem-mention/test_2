<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateTimeExtension extends AbstractExtension
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('datetime_with_timezone', [$this, 'getDateTimeWithTimezone']),
        ];
    }

    public function getDateTimeWithTimezone(\DateTimeImmutable $datetime, string $timezone): string
    {
        $datetimeWithTimezone = $datetime->setTimezone(new \DateTimeZone($timezone));

        return sprintf(
            '%s %s %s (UTC %s)',
            $datetimeWithTimezone->format('d/m/Y'),
            $this->translator->trans('pel.at'),
            $datetimeWithTimezone->format('H:i'),
            $datetimeWithTimezone->format('P')
        );
    }
}
