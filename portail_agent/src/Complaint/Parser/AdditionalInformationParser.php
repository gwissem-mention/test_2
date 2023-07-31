<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Entity\AdditionalInformation;
use App\Entity\Witness;

class AdditionalInformationParser
{
    public function __construct(private readonly PhoneParser $phoneParser)
    {
    }

    public function parse(object $additionalInformation): AdditionalInformation
    {
        $additionalInformationParsed = new AdditionalInformation();

        $additionalInformationParsed
            ->setCctvPresent($additionalInformation->cctvPresent->code)
            ->setCctvAvailable($additionalInformation->cctvAvailable)
            ->setSuspectsKnown($additionalInformation->suspectsChoice)
            ->setSuspectsKnownText($additionalInformation->suspectsText)
            ->setWitnessesPresent($additionalInformation->witnessesPresent)
            ->setFsiVisit($additionalInformation->fsiVisit)
            ->setObservationMade($additionalInformation->observationMade);

        foreach ($additionalInformation->witnesses as $witness) {
            $additionalInformationParsed->addWitness(
                (new Witness())
                    ->setDescription($witness->description ?? null)
                    ->setPhone($witness->phone->number ? $this->phoneParser->parse($witness->phone) : null)
                    ->setEmail($witness->email ?? null)
            );
        }

        return $additionalInformationParsed;
    }
}
