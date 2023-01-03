<?php

declare(strict_types=1);

namespace App\Twig;

use App\Form\Model\Identity\PhoneModel;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PhoneExtension extends AbstractExtension
{
    public function __construct(private readonly PhoneNumberUtil $phoneUtil)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('phone_format_intl', [$this, 'getPhoneIntlFormatted']),
        ];
    }

    /**
     * @throws NumberParseException
     */
    public function getPhoneIntlFormatted(PhoneModel $phone): string
    {
        return $this->phoneUtil->format(
            $this->phoneUtil->parse(strval($phone->getNumber()), $phone->getCountry()),
            PhoneNumberFormat::INTERNATIONAL
        );
    }
}
