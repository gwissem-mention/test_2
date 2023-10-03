<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Complaint;
use App\Entity\Facts;

class FactsDTO
{
    private const TIME_INFORMATION_KNOWN = 'connu';
    private const TIME_INFORMATION_TIME_UNKNOWN = 'horaire_inconnu';
    private const TIME_INFORMATION_DATE_UNKNOWN = 'date_inconnu';

    private const NATURES_PLACE_TRANSPORTS = [
        'AIRE D\'AUTOROUTE',
        'GARE ROUTIERE',
        'AUTOBUS',
        'ARRET DE BUS',
        'TRAMWAY',
        'ARRET DE TRAMWAY',
        'METRO',
        'STATION DE METRO',
        'RER',
        'GARE RER',
        'TRAIN',
        'GARE TER',
        'GARE TGV',
        'AVION',
        'AEROPORT',
        'BATEAU',
        'PORT',
    ];

    /** @var array<int|string> */
    private array $presentation;
    private string $manop;
    private string $startAddressCountry;
    private string $startAddressDepartment;
    private string $startAddressPostalCode;
    private string $startAddressInseeCode;
    private string $startAddressCity;
    private string $startAddressDepartmentNumber;
    private ?string $endAddress = null;
    private ?string $endAddressCountry = null;
    private ?string $endAddressDepartment = null;
    private ?string $endAddressPostalCode = null;
    private ?string $endAddressInseeCode = null;
    private ?string $endAddressCity = null;
    private ?string $endAddressDepartmentNumber = null;
    private string $localisation;
    private string $unknownLocalisation;
    private ?string $timeInformation;
    private ?string $date;
    private ?string $hour;
    private ?string $minutes;
    private ?string $startDateFormatted;
    private ?string $startHourFormatted;
    private ?string $startMinutesFormatted;
    private ?string $endDateFormatted;
    private ?string $endHourFormatted;
    private ?string $endMinutesFormatted;
    private string $start;
    private string $end;
    private string $noViolence;
    //    private string $noOrientation;
    private string $violenceDescription;
    //    private string $orientation;
    private string $hasHarmPhysique;
    private string $hasHarmPhysiqueDescription;
    private bool $isNaturePlaceTransports;
    private string $addressAdditionalInformation;
    private string $place;
    private string $callingPhone;
    private string $factsWebsite;
    private string $exactDateKnown;
    private string $startAddress;
    private string $hasObjectsWithAmount;

    public function __construct(Complaint $complaint)
    {
        /** @var Facts $facts */
        $facts = $complaint->getFacts();
        $this->presentation = str_replace(
            [Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION, Facts::NATURE_OTHER],
            ['Vol', 'Dégradation', 'Autre atteinte aux biens'],
            $facts->getNatures() ?? []
        );
        $this->manop = $facts->getDescription() ?? '';
        $this->startAddress = $facts->getStartAddress() ?? '';
        $this->startAddressCountry = $facts->getStartAddressCountry() ?? '';
        $this->startAddressDepartment = $facts->getStartAddressDepartment() ?? '';
        $this->startAddressPostalCode = $facts->getStartAddressPostalCode() ?? '';
        $this->startAddressInseeCode = $facts->getStartAddressInseeCode() ?? '';
        $this->startAddressCity = $facts->getStartAddressCity() ?? '';
        $this->startAddressDepartmentNumber = strval($facts->getStartAddressDepartmentNumber());
        $this->localisation = $facts->getPlace() ?? '';
        $this->unknownLocalisation = true === $facts->isExactPlaceUnknown() && false === $facts->hasExactAddress() ? '1' : '';

        if (Facts::EXACT_HOUR_KNOWN_YES === $facts->getExactHourKnown() && $facts->isExactDateKnown()) {
            $this->timeInformation = self::TIME_INFORMATION_KNOWN;
        } elseif (!$facts->isExactDateKnown()) {
            $this->timeInformation = self::TIME_INFORMATION_DATE_UNKNOWN;
        } elseif (Facts::EXACT_HOUR_KNOWN_NO === $facts->getExactHourKnown() || Facts::EXACT_HOUR_KNOWN_DONT_KNOW === $facts->getExactHourKnown()) {
            $this->timeInformation = self::TIME_INFORMATION_TIME_UNKNOWN;
        }

        $this->date = null !== ($date = $facts->getStartDate()) ? $date->format('d/m/Y') : '';
        $this->hour = null !== ($hour = $facts->getStartHour()) ? $hour->format('H') : '';
        $this->minutes = null !== ($hour = $facts->getStartHour()) ? $hour->format('i') : '';
        $this->startDateFormatted = null !== ($date = $facts->getStartDate()) ? $date->format('d/m/Y') : '';
        $this->startHourFormatted = null !== ($hour = $facts->getStartHour()) ? $hour->format('H') : '';
        $this->startMinutesFormatted = null !== ($hour = $facts->getStartHour()) ? $hour->format('i') : '';
        $this->endDateFormatted = null !== ($date = $facts->getEndDate()) ? $date->format('d/m/Y') : '';
        $this->endHourFormatted = null !== ($hour = $facts->getEndHour()) ? $hour->format('H') : '';
        $this->endMinutesFormatted = null !== ($hour = $facts->getEndHour()) ? $hour->format('i') : '';

        $debut = $facts->getStartDate();
        $fin = $facts->getEndDate();
        $startHour = $facts->getStartHour();
        $endHour = $facts->getEndHour();

        if (null !== $debut && null !== $fin && null !== $startHour && null !== $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $finFormatted = $fin->format('d/m/Y');
            $this->start = $debutFormatted.' à '.$startHour->format('H:i');
            $this->end = $finFormatted.' à '.$endHour->format('H:i');
        } elseif (null !== $debut && null === $startHour && null === $fin && null === $endHour) {
            $this->start = $debut->format('d/m/Y à 00h00');
            $this->end = $debut->format('d/m/Y à 23h59');
        } elseif (null !== $debut && null !== $startHour && null === $fin && null === $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $startHour = \DateTime::createFromInterface($startHour);
            $endHour = $startHour->modify('+5 minutes');
            $this->start = $debutFormatted.' à '.$startHour->format('H:i');
            $this->end = $debutFormatted.' à '.$endHour->format('H:i');
        }
        $this->noViolence = strval(!$complaint->getFacts()?->isVictimOfViolence()) ?: '';
        $this->violenceDescription = $complaint->getFacts()?->getVictimOfViolenceText() ?? '';
        $this->hasHarmPhysique = $complaint->getFacts()?->isVictimOfViolence() ? 'oui' : 'non';
        $this->hasHarmPhysiqueDescription = true === $complaint->getFacts()?->isVictimOfViolence() ? 'pel.physical.harm.message' : '';

        $this->isNaturePlaceTransports = in_array($facts->getPlace(), self::NATURES_PLACE_TRANSPORTS);
        if ($this->isNaturePlaceTransports) {
            $this->endAddress = $facts->getEndAddress();
            $this->endAddressCountry = $facts->getEndAddressCountry();
            $this->endAddressDepartment = $facts->getEndAddressDepartment();
            $this->endAddressPostalCode = $facts->getEndAddressPostalCode();
            $this->endAddressInseeCode = $facts->getEndAddressInseeCode();
            $this->endAddressCity = $facts->getEndAddressCity();
            $this->endAddressDepartmentNumber = strval($facts->getEndAddressDepartmentNumber());
        }
        //        $this->noOrientation = !is_null($noOrientation = $facts->isNoOrientation()) ? strval($noOrientation) : '';
        //        $this->orientation = $facts->getOrientation() ?? '';
        $this->addressAdditionalInformation = $complaint->getFacts()?->getAddressAdditionalInformation() ?? '';
        $this->place = $complaint->getFacts()?->getPlace() ?? '';
        $this->callingPhone = $complaint->getFacts()?->getCallingPhone() ?? '';
        $this->factsWebsite = $complaint->getFacts()?->getWebsite() ?? '';
        $this->exactDateKnown = $complaint->getFacts()?->isExactDateKnown() ? 'Oui' : 'Non';
        $this->hasObjectsWithAmount = $complaint->hasObjectsWithAmount() ? '1' : '0';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Faits' => [
            'Faits_Expose' => implode(' / ', $this->presentation),
            'Faits_Expose_GN' => implode(' / ', $this->presentation),
            'Faits_Manop' => $this->manop,
            'Faits_Localisation_Adresse' => $this->isNaturePlaceTransports ? $this->endAddress : $this->startAddress,
            'Faits_Localisation_Pays' => $this->isNaturePlaceTransports ? $this->endAddressCountry : $this->startAddressCountry,
            'Faits_Localisation_Departement' => $this->isNaturePlaceTransports ? $this->endAddressDepartmentNumber.' - '.$this->endAddressDepartment : $this->startAddressDepartmentNumber.' - '.$this->startAddressDepartment,
            'Faits_Localisation_Codepostal' => $this->isNaturePlaceTransports ? $this->endAddressPostalCode : $this->startAddressPostalCode,
            'Faits_Localisation_Insee' => $this->isNaturePlaceTransports ? $this->endAddressInseeCode : $this->startAddressInseeCode,
            'Faits_Localisation_Commune' => $this->isNaturePlaceTransports ? $this->endAddressCity : $this->startAddressCity,
            'Faits_Localisation_HidNumDep' => $this->isNaturePlaceTransports ? $this->endAddressDepartmentNumber : $this->startAddressDepartmentNumber,
            'Faits_Adresse_Depart_Pays' => $this->isNaturePlaceTransports ? $this->startAddressCountry : null,
            'Faits_Adresse_Depart_Departement' => $this->isNaturePlaceTransports ? $this->startAddressDepartmentNumber.' - '.$this->startAddressDepartment : null,
            'Faits_Adresse_Depart_Codepostal' => $this->isNaturePlaceTransports ? $this->startAddressPostalCode : null,
            'Faits_Adresse_Depart_Insee' => $this->isNaturePlaceTransports ? $this->startAddressInseeCode : null,
            'Faits_Adresse_Depart_Commune' => $this->isNaturePlaceTransports ? $this->startAddressCity : null,
            'Faits_Adresse_Depart_HidNumDep' => $this->isNaturePlaceTransports ? $this->startAddressDepartmentNumber : null,
            'Faits_Adresse_Arrivee_Pays' => $this->isNaturePlaceTransports ? $this->endAddressCountry : null,
            'Faits_Adresse_Arrivee_Departement' => $this->isNaturePlaceTransports ? $this->endAddressDepartmentNumber.' - '.$this->endAddressDepartment : null,
            'Faits_Adresse_Arrivee_Codepostal' => $this->isNaturePlaceTransports ? $this->endAddressPostalCode : null,
            'Faits_Adresse_Arrivee_Insee' => $this->isNaturePlaceTransports ? $this->endAddressInseeCode : null,
            'Faits_Adresse_Arrivee_Commune' => $this->isNaturePlaceTransports ? $this->endAddressCity : null,
            'Faits_Adresse_Arrivee_HidNumDep' => $this->isNaturePlaceTransports ? $this->endAddressDepartmentNumber : null,
            'Faits_Localisation' => $this->localisation,
            'Faits_Localisation_Inconnue' => $this->unknownLocalisation,
            'Faits_Horaire' => $this->timeInformation,
            'Faits_Date_Affaire' => $this->date,
            'Faits_Heure' => $this->hour,
            'Faits_Minute' => $this->minutes,
            'Faits_Periode_Affaire_Debut_Date_Formate' => $this->startDateFormatted,
            'Faits_Periode_Affaire_Debut_Heure_Formate' => $this->startHourFormatted,
            'Faits_Periode_Affaire_Debut_Minute_Formate' => $this->startMinutesFormatted,
            'Faits_Periode_Affaire_Fin_Date_Formate' => $this->endDateFormatted,
            'Faits_Periode_Affaire_Fin_Heure_Formate' => $this->endHourFormatted,
            'Faits_Periode_Affaire_Fin_Minute_Formate' => $this->endMinutesFormatted,
            'Faits_Periode_Affaire_Debut' => $this->start,
            'Faits_Periode_Affaire_Fin' => $this->end,
            'Faits_Violences_Aucune' => $this->noViolence,
//            'Faits_Orientation_Aucune' => $this->noOrientation,
            'Faits_Violences_Description' => $this->violenceDescription,
//            'Faits_Orientation' => $this->orientation,
            'Faits_Prejudice_Physique' => $this->hasHarmPhysique,
            'Faits_Prejudice_Autre' => $this->hasObjectsWithAmount,
            'Faits_Prejudice_Physique_Description' => $this->hasHarmPhysiqueDescription,
            'Faits_Prejudice_Autre_Description' => '',
            'LIEU_INFORMATION_COMPLEMENTAIRES' => $this->addressAdditionalInformation,
            'NATURE_LIEU' => $this->place,
            'Nature_Lieu_Tel' => $this->callingPhone,
            'Nature_Lieu_URL' => $this->factsWebsite,
            'Date_Exacte_Faits_Connue' => $this->exactDateKnown,
        ]];
    }
}
