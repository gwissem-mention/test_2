<?php

declare(strict_types=1);

namespace App\AppEnum;

enum DocumentType: int
{
    case IdCard = 1;
    case Passport = 2;
    case ResidencePermit = 3;
    case DrivingLicense = 4;
    case VehicleRegistrationCertificate = 5;
    case HealthInsuranceCard = 6;
    case ProfessionalCard = 7;
    case Other = 8;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.id.card' => self::IdCard->value,
            'pel.passport' => self::Passport->value,
            'pel.residence.permit' => self::ResidencePermit->value,
            'pel.driving.license' => self::DrivingLicense->value,
            'pel.vehicle.registration.certificate' => self::VehicleRegistrationCertificate->value,
            'pel.health.insurance.card' => self::HealthInsuranceCard->value,
            'pel.professional.card' => self::ProfessionalCard->value,
            'pel.other' => self::Other->value,
        ];
    }

    public static function getLabel(?int $documentType = null): ?string
    {
        if (null === $documentType) {
            return null;
        }

        $label = array_search($documentType, self::getChoices(), true);

        if (false === $label) {
            return null;
        }

        return $label;
    }
}
