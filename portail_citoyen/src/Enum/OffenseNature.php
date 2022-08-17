<?php

namespace App\Enum;

enum OffenseNature: int
{
    case HouseRobbery = 1;
    case MotorizedVehicleRobbery = 2;
    case MotorizedVehicleTheft = 3;
    case OtherTheft = 4;
    case Scam = 5;
    case Degradation = 6;
    case OtherAab = 7;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'complaint.offense.nature.house.robbery' => self::HouseRobbery->value,
            'complaint.offense.nature.motorized.vehicle.robbery' => self::MotorizedVehicleRobbery->value,
            'complaint.offense.nature.motorized.vehicle.theft' => self::MotorizedVehicleTheft->value,
            'complaint.offense.nature.other.theft' => self::OtherTheft->value,
            'complaint.offense.nature.scam' => self::Scam->value,
            'complaint.offense.nature.degradation' => self::Degradation->value,
            'complaint.offense.nature.other.aab' => self::OtherAab->value,
        ];
    }
}
