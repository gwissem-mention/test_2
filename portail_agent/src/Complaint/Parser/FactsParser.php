<?php

namespace App\Complaint\Parser;

use App\Entity\Facts;
use Symfony\Contracts\Translation\TranslatorInterface;

class FactsParser
{
    public function __construct(private readonly TranslatorInterface $translator, private readonly DateParser $dateParser)
    {
    }

    public function parse(object $facts): Facts
    {
        $factsParsed = new Facts();

        $exactHourKnown = match ($facts->offenseDate->choiceHour) {
            'yes' => Facts::EXACT_HOUR_KNOWN_YES,
            'no' => Facts::EXACT_HOUR_KNOWN_NO,
            default => Facts::EXACT_HOUR_KNOWN_DONT_KNOW,
        };

        $department = isset($facts->address->startAddress->citycode) ? substr($facts->address->startAddress->citycode, 0, 2) : '';

        $factsParsed
            ->setNatures([Facts::NATURE_ROBBERY]) // TODO to be removed when the summary contains the nature by object, in the meantime,setNatures in ObjectsParser
            ->setDescription($facts->description)
            ->setExactPlaceUnknown(!$facts->address->addressOrRouteFactsKnown)
            ->setPlace($this->translator->trans($facts->placeNature->label))
            ->setExactDateKnown($facts->offenseDate->exactDateKnown)
            ->setStartDate($this->dateParser->parse($facts->offenseDate->startDate))
            ->setEndDate($facts->offenseDate->endDate ? $this->dateParser->parse($facts->offenseDate->endDate) : null)
            ->setStartAddress($facts->address->startAddress ? $facts->address->startAddress->label : '')
            ->setEndAddress($facts->address->endAddress->label ?? null)
            ->setCountry('France')
            ->setDepartment($department)
            ->setDepartmentNumber((int) $department)
            ->setCity($facts->address->startAddress->city ?? '')
            ->setPostalCode($facts->address->startAddress->postcode ?? '')
            ->setInseeCode($facts->address->startAddress->citycode ?? '')
            ->setExactHourKnown($exactHourKnown)
            ->setStartHour($facts->offenseDate->startHour ? $this->dateParser->parse($facts->offenseDate->startHour) : null)
            ->setEndHour($facts->offenseDate->endHour ? $this->dateParser->parse($facts->offenseDate->endHour) : null)
            ->setAddressAdditionalInformation($facts->address->addressAdditionalInformation ?? null)
            ->setVictimOfViolence($facts->victimOfViolence ?? null)
            ->setVictimOfViolenceText($facts->victimOfViolenceText ?? null);

        return $factsParsed;
    }
}
