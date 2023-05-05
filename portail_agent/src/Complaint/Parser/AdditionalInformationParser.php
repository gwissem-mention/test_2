<?php

namespace App\Complaint\Parser;

use App\Entity\AdditionalInformation;

class AdditionalInformationParser
{
    public function parse(object $additionalInformation): AdditionalInformation
    {
        $additionalInformationParsed = new AdditionalInformation();

        $additionalInformationParsed
            ->setCctvPresent($additionalInformation->cctvPresent->code)
            ->setCctvAvailable($additionalInformation->cctvAvailable)
            ->setSuspectsKnown($additionalInformation->suspectsChoice)
            ->setSuspectsKnownText($additionalInformation->suspectsText)
            ->setWitnessesPresent($additionalInformation->witnesses)
            ->setWitnessesPresentText($additionalInformation->witnessesText)
            ->setFsiVisit($additionalInformation->fsiVisit)
            ->setObservationMade($additionalInformation->observationMade);

        return $additionalInformationParsed;
    }
}
