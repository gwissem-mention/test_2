<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Complaint;

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

    public function __construct(Complaint $complaint)
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
//            'Tel_Bureau_Declarant' => $this->declarantOfficePhone,
            'Tel_Portable_Declarant' => $this->declarantMobilePhone,
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
            'Mail_Personne_Morale' => $this->email,
            'Tel_Bureau_Declarant' => $this->phone,
        ]];
    }
}
