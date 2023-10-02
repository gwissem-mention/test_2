<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Witness;
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
    private string $cctvPresent;
    private string $witnessesText = '';

    public function __construct(Complaint $complaint, UnitRepository $unitRepository)
    {
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
            default => 'Inconnu',
        };
        $witnessesCount = $complaint->getAdditionalInformation()?->getWitnesses()->count();
        $complaint->getAdditionalInformation()?->getWitnesses()->map(function (Witness $witness) use ($witnessesCount): void {
            $this->witnessesText .= $witness->getDescription();
            if ($witnessesCount > 1) {
                $this->witnessesText .= ' ';
            }
        });
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
            'Enregistrement_Video' => $this->cctvPresent,
            'TEMOINS_DESCRIPTION' => $this->witnessesText,
        ]];
    }
}
