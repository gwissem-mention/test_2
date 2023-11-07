<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObjects\AbstractObject;
use App\Entity\Identity;
use App\Entity\Witness;
use App\Referential\Repository\UnitRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FactsDTO
{
    private const TIME_INFORMATION_KNOWN = 'connu';
    private const TIME_INFORMATION_TIME_UNKNOWN = 'horaire_inconnu';
    private const TIME_INFORMATION_DATE_UNKNOWN = 'date_inconnu';
    private const NATURE_PLACE_INTERNET = 'INTERNET';
    private const NATURE_PLACE_TEL = 'RESEAU TELEPHONIQUE';

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

    private const GIRONDE_DEPARTMENT_NUMBER = '33';
    private const TIMEZONE = 'Europe/Paris';

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
    private string $localisation = '';
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
    private string $start = '';
    private string $end = '';
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
    private string $estimation;
    private bool $isStartAddressGironde = false;
    private bool $isEndAddressGironde = false;
    private string $suspectsKnown;
    private string $suspectsKnownText;
    private string $witnessesPresent;
    private string $fsiVisit;
    private string $observationMade;
    private string $cctvAvailable;
    private string $appointmentUnit;
    private string $appointmentAsked;
    private string $cctvPresent;
    private string $witnessesText = '';
    private string $urlAPIPJ;

    public function __construct(Complaint $complaint, UnitRepository $unitRepository, UrlGeneratorInterface $urlGenerator)
    {
        /** @var Facts $facts */
        $facts = $complaint->getFacts();
        /** @var Identity $identity */
        $identity = $complaint->getIdentity();
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

        if (Facts::EXACT_HOUR_KNOWN_YES === $facts->getExactHourKnown() && $facts->isExactDateKnown()) {
            $this->timeInformation = self::TIME_INFORMATION_KNOWN;
        } elseif (!$facts->isExactDateKnown()) {
            $this->timeInformation = self::TIME_INFORMATION_DATE_UNKNOWN;
        } elseif (Facts::EXACT_HOUR_KNOWN_NO === $facts->getExactHourKnown() || Facts::EXACT_HOUR_KNOWN_DONT_KNOW === $facts->getExactHourKnown()) {
            $this->timeInformation = self::TIME_INFORMATION_TIME_UNKNOWN;
        }

        $debut = $facts->getStartDate();
        $fin = $facts->getEndDate();
        $startHour = $facts->getStartHour() ? \DateTime::createFromInterface($facts->getStartHour()) : null;
        $endHour = $facts->getEndHour() ? \DateTime::createFromInterface($facts->getEndHour()) : null;

        $startHour?->setDate((int) $debut?->format('Y'), (int) $debut?->format('m'), (int) $debut?->format('d'))->setTimezone(new \DateTimeZone(self::TIMEZONE));
        $endHour?->setDate((int) $debut?->format('Y'), (int) $debut?->format('m'), (int) $debut?->format('d'))->setTimezone(new \DateTimeZone(self::TIMEZONE));

        $this->date = null !== $debut ? $debut->format('d/m/Y') : '';
        $this->hour = null !== $startHour ? $startHour->format('H') : '';
        $this->minutes = null !== $startHour ? $startHour->format('i') : '';
        $this->startDateFormatted = $debut?->format('d/m/Y') ?? '';
        $this->startHourFormatted = $startHour?->format('H') ?? '';
        $this->startMinutesFormatted = $startHour?->format('i') ?? '';
        $this->endDateFormatted = $fin?->format('d/m/Y') ?? $this->startDateFormatted;
        $this->endHourFormatted = $endHour?->format('H') ?? $this->startHourFormatted;
        $this->endMinutesFormatted = $endHour?->format('i') ?? $this->startMinutesFormatted;

        if (null !== $debut && null !== $fin && null !== $startHour && null !== $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $finFormatted = $fin->format('d/m/Y');
            $this->start = $debutFormatted.' à '.$startHour->format('H:i');
            $this->end = $finFormatted.' à '.$endHour->format('H:i');
        } elseif (null !== $debut && null === $startHour && null === $fin && null === $endHour) {
            $this->start = $debut->format('d/m/Y').' à 00h00';
            $this->end = $debut->format('d/m/Y').'à 23h59';
        } elseif (null !== $debut && null !== $startHour && null === $fin && null !== $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $startHour = \DateTime::createFromInterface($startHour);
            $endHour = \DateTime::createFromInterface($endHour);
            $this->start = $debutFormatted.' à '.$startHour->format('H:i');
            $this->end = $debutFormatted.' à '.$endHour->format('H:i');
        } elseif (null !== $debut && null !== $startHour && null === $fin && null === $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $startHour = \DateTime::createFromInterface($startHour);
            $endHour = clone $startHour;
            $endHour->modify('+5 minutes');
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

            if (self::GIRONDE_DEPARTMENT_NUMBER === substr($facts->getStartAddressInseeCode() ?? '', 0, 2)) {
                $this->isStartAddressGironde = true;
            }
            if (self::GIRONDE_DEPARTMENT_NUMBER === substr($facts->getEndAddressInseeCode() ?? '', 0, 2)) {
                $this->isEndAddressGironde = true;
            }
        }
        //        $this->noOrientation = !is_null($noOrientation = $facts->isNoOrientation()) ? strval($noOrientation) : '';
        //        $this->orientation = $facts->getOrientation() ?? '';
        $this->addressAdditionalInformation = $complaint->getFacts()?->getAddressAdditionalInformation() ?? '';
        $this->place = $complaint->getFacts()?->getPlace() ?? '';
        $this->callingPhone = $complaint->getFacts()?->getCallingPhone() ? str_replace(' ', '', $complaint->getFacts()->getCallingPhone()) : '';
        $this->factsWebsite = $complaint->getFacts()?->getWebsite() ?? '';
        $this->exactDateKnown = $complaint->getFacts()?->isExactDateKnown() ? 'Oui' : 'Non';
        $this->hasObjectsWithAmount = $complaint->hasObjectsWithAmount() ? '1' : '0';
        $this->estimation = '1' === $this->hasObjectsWithAmount ? (string) array_sum(array_map(fn (AbstractObject $object) => $object->getAmount(), $complaint->getObjects()->toArray())) : '';
        $this->suspectsKnown = $complaint->getAdditionalInformation()?->isSuspectsKnown() ? 'Oui' : 'Non';
        $this->suspectsKnownText = $complaint->getAdditionalInformation()?->getSuspectsKnownText() ?? '';
        $this->witnessesPresent = $complaint->getAdditionalInformation()?->isWitnessesPresent() ? 'Oui' : 'Non';
        $this->fsiVisit = $complaint->getAdditionalInformation()?->isFsiVisit() ? 'Oui' : 'Non';
        $this->observationMade = $complaint->getAdditionalInformation()?->isObservationMade() ? 'Oui' : 'Non';
        $this->cctvAvailable = $complaint->getAdditionalInformation()?->isCctvAvailable() ? 'Oui' : 'Non';
        $this->appointmentAsked = $complaint->isAppointmentAsked() ? 'Oui' : 'Non';
        $this->appointmentUnit = $unitRepository->findOneBy(['code' => $complaint->getUnitAssigned()])?->getName() ?? '';
        $this->cctvPresent = match ($complaint->getAdditionalInformation()?->getCctvPresent()) {
            AdditionalInformation::CCTV_PRESENT_YES => 'Oui',
            AdditionalInformation::CCTV_PRESENT_NO => 'Non',
            AdditionalInformation::CCTV_PRESENT_DONT_KNOW => 'Je ne sais pas',
            default => 'Inconnu',
        };
        $witnessesCount = $complaint->getAdditionalInformation()?->getWitnesses()->count();
        $complaint->getAdditionalInformation()?->getWitnesses()->map(function (Witness $witness) use ($witnessesCount): void {
            $this->witnessesText .= $witness->getDescription();
            if ($witnessesCount > 1) {
                $this->witnessesText .= ' ';
            }
        });
        $this->urlAPIPJ = $urlGenerator->generate('api_download_attachments', ['complaintFrontId' => $complaint->getFrontId()], UrlGeneratorInterface::ABSOLUTE_URL);

        $this->setLocalisation($facts, $identity);
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Faits' => [
            'Faits_Expose' => implode(' / ', $this->presentation),
            'Faits_Manop' => $this->manop,
            'Faits_Localisation_Adresse' => $this->isNaturePlaceTransports ? ($this->isStartAddressGironde && !$this->isEndAddressGironde ? $this->startAddress : $this->endAddress) : $this->startAddress,
            'Faits_Localisation_Pays' => $this->isNaturePlaceTransports ? ($this->isStartAddressGironde && !$this->isEndAddressGironde ? $this->startAddressCountry : $this->endAddressCountry) : $this->startAddressCountry,
            'Faits_Localisation_Departement' => $this->isNaturePlaceTransports ? ($this->isStartAddressGironde && !$this->isEndAddressGironde ? $this->startAddressDepartmentNumber.' - '.$this->startAddressDepartment : $this->endAddressDepartmentNumber.' - '.$this->endAddressDepartment) : $this->startAddressDepartmentNumber.' - '.$this->startAddressDepartment,
            'Faits_Localisation_Codepostal' => $this->isNaturePlaceTransports ? ($this->isStartAddressGironde && !$this->isEndAddressGironde ? $this->startAddressPostalCode : $this->endAddressPostalCode) : $this->startAddressPostalCode,
            'Faits_Localisation_Insee' => $this->isNaturePlaceTransports ? ($this->isStartAddressGironde && !$this->isEndAddressGironde ? $this->startAddressInseeCode : $this->endAddressInseeCode) : $this->startAddressInseeCode,
            'Faits_Localisation_Commune' => $this->isNaturePlaceTransports ? ($this->isStartAddressGironde && !$this->isEndAddressGironde ? $this->startAddressCity : $this->endAddressCity) : $this->startAddressCity,
            'Faits_Localisation_HidNumDep' => $this->isNaturePlaceTransports ? ($this->isStartAddressGironde && !$this->isEndAddressGironde ? $this->startAddressDepartmentNumber : $this->endAddressDepartmentNumber) : $this->startAddressDepartmentNumber,
            'Faits_Adresse_Depart' => $this->isNaturePlaceTransports ? $this->startAddress : null,
            'Faits_Adresse_Depart_Pays' => $this->isNaturePlaceTransports ? $this->startAddressCountry : null,
            'Faits_Adresse_Depart_Departement' => $this->isNaturePlaceTransports ? $this->startAddressDepartmentNumber.' - '.$this->startAddressDepartment : null,
            'Faits_Adresse_Depart_Codepostal' => $this->isNaturePlaceTransports ? $this->startAddressPostalCode : null,
            'Faits_Adresse_Depart_Insee' => $this->isNaturePlaceTransports ? $this->startAddressInseeCode : null,
            'Faits_Adresse_Depart_Commune' => $this->isNaturePlaceTransports ? $this->startAddressCity : null,
            'Faits_Adresse_Depart_HidNumDep' => $this->isNaturePlaceTransports ? $this->startAddressDepartmentNumber : null,
            'Faits_Adresse_Arrivee' => $this->isNaturePlaceTransports ? $this->endAddress : null,
            'Faits_Adresse_Arrivee_Pays' => $this->isNaturePlaceTransports ? $this->endAddressCountry : null,
            'Faits_Adresse_Arrivee_Departement' => $this->isNaturePlaceTransports ? $this->endAddressDepartmentNumber.' - '.$this->endAddressDepartment : null,
            'Faits_Adresse_Arrivee_Codepostal' => $this->isNaturePlaceTransports ? $this->endAddressPostalCode : null,
            'Faits_Adresse_Arrivee_Insee' => $this->isNaturePlaceTransports ? $this->endAddressInseeCode : null,
            'Faits_Adresse_Arrivee_Commune' => $this->isNaturePlaceTransports ? $this->endAddressCity : null,
            'Faits_Adresse_Arrivee_HidNumDep' => $this->isNaturePlaceTransports ? $this->endAddressDepartmentNumber : null,
            'Faits_Localisation' => $this->localisation,
            'Faits_Localisation_Inconnue' => '',
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
            'Faits_Prejudice_Autre_Description' => $this->estimation,
            'Lieu_Information_Complementaires' => $this->addressAdditionalInformation,
            'Nature_Lieu' => $this->place,
            'Nature_Lieu_Tel' => $this->callingPhone,
            'Nature_Lieu_URL' => $this->factsWebsite,
            'Date_Exacte_Faits_Connue' => $this->exactDateKnown,
            'Suspects_Informations' => $this->suspectsKnown,
            'Suspects_Description' => $this->suspectsKnownText,
            'Temoins_Presents' => $this->witnessesPresent,
            'Intervention_Fsi' => $this->fsiVisit,
            'Constat_Relev_Effectues' => $this->observationMade,
            'Video_Disponible' => $this->cctvAvailable,
            'Unite_Rdv' => $this->appointmentUnit,
            'Rdv_Souhaite' => $this->appointmentAsked,
            'Enregistrement_Video' => $this->cctvPresent,
            'Temoins_Description' => $this->witnessesText,
            'URL_API_PJ' => $this->urlAPIPJ,
        ]];
    }

    private function setLocalisation(Facts $facts, Identity $identity): void
    {
        if (self::NATURE_PLACE_INTERNET === $facts->getPlace()) {
            if (null !== $facts->getWebsite()) {
                $this->localisation .= sprintf(
                    'La personne déclarante indique que l\'infraction a été commise sur internet sur le site dont l\' URL est %s. ',
                    $facts->getWebsite(),
                );
            } else {
                $this->localisation .= 'La personne déclarante indique que l\'infraction a été commise sur internet sur un site dont il ignore l\'URL. ';
            }
        } elseif (self::NATURE_PLACE_TEL === $facts->getPlace()) {
            if (null === $facts->getCallingPhone()) {
                $this->localisation = 'La personne déclarante indique ignorer le numéro de la ligne téléphonique incriminé. ';
            } else {
                $this->localisation .= sprintf(
                    'La personne déclarante indique comme numéro de la ligne téléphonique incriminé %s. ',
                    $facts->getCallingPhone(),
                );
            }
        } elseif (null === $facts->getEndAddress()) {
            $this->localisation .= sprintf(
                'La personne déclarante indique comme adresse pour le lieu de commission des faits %s et comme nature de lieu %s. ',
                $facts->getStartAddress(),
                $facts->getPlace()
            );
        } else {
            $this->localisation .= sprintf(
                'La personne déclarante indique que les faits on été commis entre %s et %s dans %s. ',
                $facts->getStartAddress(),
                $facts->getEndAddress(),
                $facts->getPlace()
            );
        }

        if (null !== $facts->getAddressAdditionalInformation()) {
            $this->localisation .= sprintf(
                '%s %s %s nous précise %s. ',
                Identity::CIVILITY_MALE === $identity->getCivility() ? 'M.' : 'Mme',
                $identity->getLastname(),
                $identity->getFirstname(),
                $facts->getAddressAdditionalInformation()
            );
        }

        $this->localisation .= 'Sur la présence de violences au moment des faits, la personne déclarante indique : ';
    }
}
