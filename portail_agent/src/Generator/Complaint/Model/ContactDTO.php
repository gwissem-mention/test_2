<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Witness;
use App\Referential\Repository\UnitRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContactDTO
{
    private string $claimsLegalAction;
    private string $declarantEmail;
    private string $declarantHomePhone;
    //    private string $declarantOfficePhone;
    private string $declarantMobilePhone;
    //    private string $appointementChoice;
    //    private string $contactWindow;
    //    private string $contactPeriod;
    private string $cons;
    private string $consTel;
    private string $consEmail;
    private string $consPortalis;
    private string $declarationNumber;
    private string $createdAt;
    private string $franceConnected;
    private string $job;
    private string $email;
    private string $phone;
    private string $addressAdditionalInformation;
    private string $place;
    private string $callingPhone;
    private string $factsWebsite;
    private string $exactDateKnown;
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
        $consentEmailOrSMS = true === $complaint->isConsentContactEmail() || true === $complaint->isConsentContactSMS();
        $this->claimsLegalAction = true === $consentEmailOrSMS ? 'Oui' : 'Non';
        $this->declarantEmail = $complaint->getIdentity()?->getEmail() ?? '';
        $this->declarantHomePhone = $complaint->getIdentity()?->getHomePhone() ? str_replace(' ', '', $complaint->getIdentity()->getHomePhone()) : '';
        //        $this->declarantOfficePhone = $complaint->getIdentity()?->getOfficePhone() ?? '';
        $this->declarantMobilePhone = $complaint->getIdentity()?->getMobilePhone() ? str_replace(' ', '', $complaint->getIdentity()->getMobilePhone()) : '';
        //        $this->appointementChoice = !is_null($complaint->getAppointmentDate()) ? $complaint->getAppointmentDate()->format('d/m/Y H').'h' : '';
        //        $this->contactWindow = $complaint->getContactWindow() ?? '';
        //        $this->contactPeriod = $complaint->getContactPeriod() ?? '';
        $this->cons = true === $consentEmailOrSMS ? 'Oui' : 'Non';
        $this->consTel = true === $complaint->isConsentContactSMS() ? 'Oui' : 'Non';
        $this->consEmail = true === $complaint->isConsentContactEmail() ? 'Oui' : 'Non';
        $this->consPortalis = true === $complaint->isConsentContactPortal() ? 'Oui' : 'Non';
        $this->declarationNumber = $complaint->getDeclarationNumber();
        $this->createdAt = $complaint->getCreatedAt()?->format('d/m/Y H:i:s') ?? '';
        $this->franceConnected = $complaint->isFranceConnected() ? 'Oui' : 'Non';
        $this->job = $complaint->getIdentity()?->getJob() ?? '';
        $this->email = $complaint->getCorporationRepresented()?->getContactEmail() ?? '';
        $this->phone = $complaint->getCorporationRepresented()?->getPhone() ?? '';
        $this->addressAdditionalInformation = $complaint->getFacts()?->getAddressAdditionalInformation() ?? '';
        $this->place = $complaint->getFacts()?->getPlace() ?? '';
        $this->callingPhone = $complaint->getFacts()?->getCallingPhone() ? str_replace(' ', '', $complaint->getFacts()->getCallingPhone()) : '';
        $this->factsWebsite = $complaint->getFacts()?->getWebsite() ?? '';
        $this->exactDateKnown = $complaint->getFacts()?->isExactDateKnown() ? 'Oui' : 'Non';
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
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getArray(): array
    {
        return ['Contact' => [
            'Demande_Suites_Judiciaires' => $this->claimsLegalAction,
            'Mail_Declarant' => $this->declarantEmail,
            'Tel_Domicile_Declarant' => $this->declarantHomePhone,
            'Tel_Bureau_Declarant' => $this->phone,
            'Tel_Portable_Declarant' => $this->declarantMobilePhone,
            'Mail_Personne_Morale' => $this->email,
//            'Choix_Rendez_Vous' => $this->appointementChoice,
//            'Creaneau_Contact' => $this->contactWindow,
//            'Periode_Contact' => $this->contactPeriod,
            'CONS' => $this->cons,
            'CONS_Tel' => $this->consTel,
            'CONS_Mail' => $this->consEmail,
            'CONS_Portalis' => $this->consPortalis,
            'Numero_PEL' => $this->declarationNumber,
            'GDH_Validation_PEL' => $this->createdAt,
            'France_Connect' => $this->franceConnected,
            'Profession_INSEE_PEL' => $this->job,
            'Lieu_Information_Complementaires' => $this->addressAdditionalInformation,
            'Nature_Lieu' => $this->place,
            'Nature_Lieu_Tel' => $this->callingPhone,
            'Nature_Lieu_URL' => $this->factsWebsite,
            'Date_Exacte_Faits_Connue' => $this->exactDateKnown,
            'Suspects_Informations' => $this->suspectsKnown,
            'Suspects_Description' => $this->suspectsKnownText,
            'Temoins_Presents' => $this->witnessesPresent,
            'Temoins_Description' => $this->witnessesText,
            'Intervention_Fsi' => $this->fsiVisit,
            'Constat_Relev_Effectues' => $this->observationMade,
            'Enregistrement_Video' => $this->cctvPresent,
            'Video_Disponible' => $this->cctvAvailable,
            'Rdv_Souhaite' => $this->appointmentAsked,
            'Unite_Rdv' => $this->appointmentUnit,
            'URL_API_PJ' => $this->urlAPIPJ,
        ]];
    }
}
