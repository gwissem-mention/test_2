<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;

class ComplaintXmlAdditionalInformationPN
{
    public function set(Complaint $complaint): string
    {
        $exposedFacts = '. ';
        $exposedFacts .= $this->setJob($complaint);

        return $exposedFacts;
    }

    private function setJob(Complaint $complaint): string
    {
        return 'Profession saisie par le citoyen : '.$complaint->getIdentity()?->getJob();
    }
}
