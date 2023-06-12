<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Complaint;

class ContactDTO
{
    //    private string $claimsLegalAction;
    private string $declarantEmail;
    private string $declarantHomePhone;
    //    private string $declarantOfficePhone;
    private string $declarantMobilePhone;
    //    private string $appointementChoice;
    //    private string $contactWindow;
    //    private string $contactPeriod;

    public function __construct(Complaint $complaint)
    {
        //        $this->claimsLegalAction = true === $complaint->getClaimsLegalAction() ? 'Oui' : 'Non';
        $this->declarantEmail = $complaint->getIdentity()?->getEmail() ?? '';
        $this->declarantHomePhone = $complaint->getIdentity()?->getHomePhone() ?? '';
        //        $this->declarantOfficePhone = $complaint->getIdentity()?->getOfficePhone() ?? '';
        $this->declarantMobilePhone = $complaint->getIdentity()?->getMobilePhone() ?? '';
        //        $this->appointementChoice = !is_null($complaint->getAppointmentDate()) ? $complaint->getAppointmentDate()->format('d/m/Y H').'h' : '';
        //        $this->contactWindow = $complaint->getContactWindow() ?? '';
        //        $this->contactPeriod = $complaint->getContactPeriod() ?? '';
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getArray(): array
    {
        return ['Contact' => [
//            'Demandes_Suites_Judiciaires' => $this->claimsLegalAction,
            'Mail_Declarant' => $this->declarantEmail,
            'Tel_Domicile_Declarant' => $this->declarantHomePhone,
//            'Tel_Bureau_Declarant' => $this->declarantOfficePhone,
            'Tel_Portable_Declarant' => $this->declarantMobilePhone,
//            'Choix_Rendez_Vous' => $this->appointementChoice,
//            'Creaneau_Contact' => $this->contactWindow,
//            'Periode_Contact' => $this->contactPeriod,
        ]];
    }
}
