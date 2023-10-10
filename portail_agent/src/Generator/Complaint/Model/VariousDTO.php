<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Witness;
use App\Referential\Repository\UnitRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
    private string $urlAPIPJ;

    public function __construct(Complaint $complaint, UnitRepository $unitRepository, UrlGeneratorInterface $urlGenerator)
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
        $this->urlAPIPJ = $urlGenerator->generate('api_download_attachments', ['complaintFrontId' => $complaint->getFrontId()], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Divers' => [
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
}
