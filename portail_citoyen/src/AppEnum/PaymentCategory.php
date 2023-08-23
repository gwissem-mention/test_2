<?php

declare(strict_types=1);

namespace App\AppEnum;

enum PaymentCategory: int
{
    case Checkbook = 1;
    case RestaurantCheck = 2;
    case HolidaysCheck = 3;
    case CreditCard = 4;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.checkbook' => self::Checkbook->value,
            'pel.restaurant.check' => self::RestaurantCheck->value,
            'pel.holidays.check' => self::HolidaysCheck->value,
            'pel.credit.card' => self::CreditCard->value,
        ];
    }

    public static function getLabel(int $nature = null): ?string
    {
        if (null === $nature) {
            return null;
        }

        $label = array_search($nature, self::getChoices(), true);

        if (false === $label) {
            return null;
        }

        return $label;
    }
}
