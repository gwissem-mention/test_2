<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Complaint;
use App\Referential\Repository\UnitRepository;

class VariousDTO
{
    private string $franceConnected;
    private string $createdAt;
    private string $declarationNumber;
    private string $exactDateKnown;
    private string $place;
    private string $addressAdditionalInformation;
    private string $suspectsKnown;
    private string $suspectsKnownText;
    private string $witnessesPresent;
    private string $fsiVisit;
    private string $observationMade;
    private string $cctvAvailable;
    private string $appointmentUnit;
    private string $appointmentAsked;
    private string $job;
    private string $corporationContactEmail;

    public function __construct(Complaint $complaint, UnitRepository $unitRepository)
    {
        $this->franceConnected = $complaint->isFranceConnected() ? 'Oui' : 'Non';
        $this->createdAt = $complaint->getCreatedAt()?->format('d/m/Y H:i:s') ?? '';
        $this->declarationNumber = $complaint->getDeclarationNumber();
        $this->exactDateKnown = $complaint->getFacts()?->isExactDateKnown() ? 'Oui' : 'Non';
        $this->place = $complaint->getFacts()?->getPlace() ?? '';
        $this->addressAdditionalInformation = $complaint->getFacts()?->getAddressAdditionalInformation() ?? '';
        $this->suspectsKnown = $complaint->getAdditionalInformation()?->isSuspectsKnown() ? 'Oui' : 'Non';
        $this->suspectsKnownText = $complaint->getAdditionalInformation()?->getSuspectsKnownText() ?? '';
        $this->witnessesPresent = $complaint->getAdditionalInformation()?->isWitnessesPresent() ? 'Oui' : 'Non';
        $this->fsiVisit = $complaint->getAdditionalInformation()?->isFsiVisit() ? 'Oui' : 'Non';
        $this->observationMade = $complaint->getAdditionalInformation()?->isObservationMade() ? 'Oui' : 'Non';
        $this->cctvAvailable = $complaint->getAdditionalInformation()?->isCctvAvailable() ? 'Oui' : 'Non';
        $this->appointmentAsked = $complaint->isAppointmentAsked() ? 'Oui' : 'Non';
        $this->job = $complaint->getIdentity()?->getJob() ?? '';
        $this->corporationContactEmail = $complaint->getCorporationRepresented()?->getContactEmail() ?? '';

        $this->appointmentUnit = $unitRepository->findOneBy(['code' => $complaint->getUnitAssigned()])?->getName() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Divers' => [
            'DIVERS_FRANCE_CONNECTE' => $this->franceConnected,
            'DIVERS_SOUMISSION_HORAIRE' => $this->createdAt,
            'DIVERS_NUMERO_PEL' => $this->declarationNumber,
            'DIVERS_DATE_EXACTE_FAITS_CONNUE' => $this->exactDateKnown,
            'DIVERS_NATURE_LIEU' => $this->place,
            'DIVERS_LIEU_INFORMATION_COMPLEMENTAIRES' => $this->addressAdditionalInformation,
            'DIVERS_SUSPECTS_INFORMATIONS' => $this->suspectsKnown,
            'DIVERS_SUSPECTS_DESCRIPTION' => $this->suspectsKnownText,
            'DIVERS_TEMOINS_PRESENTS' => $this->witnessesPresent,
            'DIVERS_INTERVENTION_FSI' => $this->fsiVisit,
            'DIVERS_CONSTAT_RELEV_EFFECTUES' => $this->observationMade,
            'DIVERS_VIDEO_DISPONIBLE' => $this->cctvAvailable,
            'DIVERS_UNITE_RDV' => $this->appointmentUnit,
            'DIVERS_RDV_SOUHAITE' => $this->appointmentAsked,
            'DIVERS_PROFESSION_PEL' => $this->job,
            'Mail_Personne_Morale' => $this->corporationContactEmail,
        ]];
    }
}
