<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Complaint;
use App\Referential\Repository\UnitRepository;

class VariousDTO
{
    private string $suspectsKnown;
    private string $suspectsKnownText;
    private string $witnessesPresent;
    private string $fsiVisit;
    private string $observationMade;
    private string $cctvAvailable;
    private string $appointmentUnit;
    private string $appointmentAsked;
    private string $corporationContactEmail;

    public function __construct(Complaint $complaint, UnitRepository $unitRepository)
    {
        $this->suspectsKnown = $complaint->getAdditionalInformation()?->isSuspectsKnown() ? 'Oui' : 'Non';
        $this->suspectsKnownText = $complaint->getAdditionalInformation()?->getSuspectsKnownText() ?? '';
        $this->witnessesPresent = $complaint->getAdditionalInformation()?->isWitnessesPresent() ? 'Oui' : 'Non';
        $this->fsiVisit = $complaint->getAdditionalInformation()?->isFsiVisit() ? 'Oui' : 'Non';
        $this->observationMade = $complaint->getAdditionalInformation()?->isObservationMade() ? 'Oui' : 'Non';
        $this->cctvAvailable = $complaint->getAdditionalInformation()?->isCctvAvailable() ? 'Oui' : 'Non';
        $this->appointmentAsked = $complaint->isAppointmentAsked() ? 'Oui' : 'Non';
        $this->corporationContactEmail = $complaint->getCorporationRepresented()?->getContactEmail() ?? '';
        $this->appointmentUnit = $unitRepository->findOneBy(['code' => $complaint->getUnitAssigned()])?->getName() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Divers' => [
            'SUSPECTS_INFORMATIONS' => $this->suspectsKnown,
            'SUSPECTS_DESCRIPTION' => $this->suspectsKnownText,
            'TEMOINS_PRESENTS' => $this->witnessesPresent,
            'INTERVENTION_FSI' => $this->fsiVisit,
            'CONSTAT_RELEV_EFFECTUES' => $this->observationMade,
            'VIDEO_DISPONIBLE' => $this->cctvAvailable,
            'UNITE_RDV' => $this->appointmentUnit,
            'RDV_SOUHAITE' => $this->appointmentAsked,
            'Mail_Personne_Morale' => $this->corporationContactEmail,
        ]];
    }
}
