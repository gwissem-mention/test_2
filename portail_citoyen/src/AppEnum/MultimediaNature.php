<?php

declare(strict_types=1);

namespace App\AppEnum;

enum MultimediaNature: int
{
    case AcousticSpeaker = 1;
    case Tv = 2;
    case Camera = 3;
    case Computer = 4;
    case Laptop = 5;
    case UsbStick = 6;
    case Headphones = 7;
    case Smartwatch = 8;
    case Other = 9;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.multimedia.acoustic.speaker' => self::AcousticSpeaker->value,
            'pel.multimedia.tv' => self::Tv->value,
            'pel.multimedia.camera' => self::Camera->value,
            'pel.multimedia.computer' => self::Computer->value,
            'pel.multimedia.laptop' => self::Laptop->value,
            'pel.multimedia.usb.stick' => self::UsbStick->value,
            'pel.multimedia.headphones' => self::Headphones->value,
            'pel.multimedia.smartwatch' => self::Smartwatch->value,
            'pel.multimedia.other' => self::Other->value,
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
