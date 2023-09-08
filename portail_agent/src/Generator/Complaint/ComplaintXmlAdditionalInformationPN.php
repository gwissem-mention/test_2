<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;

class ComplaintXmlAdditionalInformationPN
{
    public function set(Complaint $complaint): string
    {
        $exposedFacts = '. ';
        $exposedFacts .= $this->setIntroduction($complaint);
        $exposedFacts .= $this->setJob($complaint);

        return $exposedFacts;
    }

    private function setJob(Complaint $complaint): string
    {
        return 'Profession saisie par le citoyen : '.$complaint->getIdentity()?->getJob();
    }

    private function setIntroduction(Complaint $complaint): string
    {
        return sprintf('sommes rendu destinataire de la demande de plainte en ligne, déposée sur le site internet « Plaine_En_Ligne.fr » sous le numéro d\'enregistrement : %s transmise le %s, ', $complaint->getDeclarationNumber(), $complaint->getCreatedAt()?->format('d/m/Y H:i:s'));
    }
}
