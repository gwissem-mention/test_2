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

    /** @var array<int|string> */
    private array $presentation;
    private string $manop;
    private string $country;
    private string $department;
    private string $postalCode;
    private string $inseeCode;
    private string $city;
    private string $departmentNumber;
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

    private string $job;

    public function __construct(Complaint $complaint)
    {
        /** @var Facts $facts */
        $facts = $complaint->getFacts();
        $this->presentation = str_replace(
            [Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION, Facts::NATURE_OTHER],
            ['Vol', 'Dégradation', 'Autre atteinte aux biens'],
            $facts->getNatures() ?? []
        );
        $this->job = 'Profession saisie par le citoyen : '.($complaint->getIdentity()?->getJob() ?? '');
        $this->manop = $facts->getDescription() ?? '';
        $this->country = $facts->getCountry() ?? '';
        $this->department = $facts->getDepartment() ?? '';
        $this->postalCode = $facts->getPostalCode() ?? '';
        $this->inseeCode = $facts->getInseeCode() ?? '';
        $this->city = $facts->getCity() ?? '';
        $this->departmentNumber = strval($facts->getDepartmentNumber());
        $this->localisation = $facts->getPlace() ?? '';
        $this->unknownLocalisation = true === $facts->isExactPlaceUnknown() ? '1' : '';

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
        $this->start = (null !== ($date = $facts->getStartDate()) && null !== ($hour = $facts->getStartHour())) ? ($date->format('d/m/Y').' à '.$hour->format('H:i:s')) : '';
        $this->end = (null !== ($date = $facts->getEndDate()) && null !== ($hour = $facts->getEndHour())) ? ($date->format('d/m/Y').' à '.$hour->format('H:i:s')) : '';
        $this->noViolence = strval(!$complaint->getFacts()?->isVictimOfViolence()) ?: '';
        $this->violenceDescription = $complaint->getFacts()?->getVictimOfViolenceText() ?? '';
        $this->hasHarmPhysique = $complaint->getFacts()?->isVictimOfViolence() ? 'oui' : 'non';
        $this->hasHarmPhysiqueDescription = true === $complaint->getFacts()?->isVictimOfViolence() ? 'pel.physical.harm.message' : '';

        //        $this->noOrientation = !is_null($noOrientation = $facts->isNoOrientation()) ? strval($noOrientation) : '';
        //        $this->orientation = $facts->getOrientation() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Faits' => [
            'Faits_Expose' => implode(' / ', $this->presentation).'. '.$this->job,
            'Faits_Manop' => $this->manop,
            'Faits_Localisation_Pays' => $this->country,
            'Faits_Localisation_Departement' => $this->departmentNumber.' - '.$this->department,
            'Faits_Localisation_Codepostal' => $this->postalCode,
            'Faits_Localisation_Insee' => $this->inseeCode,
            'Faits_Localisation_Commune' => $this->city,
            'Faits_Localisation_HidNumDep' => $this->departmentNumber,
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
            'Faits_Prejudice_Autre' => '',
            'Faits_Prejudice_Physique_Description' => $this->hasHarmPhysiqueDescription,
            'Faits_Prejudice_Autre_Description' => '',
        ]];
    }
}
