<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Entity\Facts;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @phpstan-import-type JsonDate from DateParser
 *
 * @phpstan-type JsonFacts object{
 *      description: string,
 *      offenseDate: object,
 *      placeNature: object{label: string},
 *      address: object{
 *           addressOrRouteFactsKnown: bool,
 *           startAddress: object{
 *               label: string,
 *               city: string,
 *               postcode: string,
 *               citycode: string,
 *           },
 *           endAddress: object{
 *                label: string,
 *                city: string,
 *                postcode: string,
 *                citycode: string,
 *           },
 *       addressAdditionalInformation: string|null,
 *       },
 *      victimOfViolence: bool,
 *      victimOfViolenceText: string|null,
 *      offenseDate: object{
 *           choiceHour: string,
 *           exactDateKnown: bool,
 *           startDate: JsonDate,
 *           endDate: JsonDate|null,
 *           startHour: JsonDate|null,
 *           endHour: JsonDate|null,
 *      },
 *  }
 */
class FactsParser
{
    public function __construct(private readonly TranslatorInterface $translator, private readonly DateParser $dateParser)
    {
    }

    /**
     * @param JsonFacts                                $facts
     * @param array<object{status: object{code: int}}> $objects
     *
     * @throws \Exception
     */
    public function parse(object $facts, array $objects): Facts
    {
        $factsParsed = new Facts();

        $exactHourKnown = match ($facts->offenseDate->choiceHour) {
            'yes' => Facts::EXACT_HOUR_KNOWN_YES,
            'no' => Facts::EXACT_HOUR_KNOWN_NO,
            default => Facts::EXACT_HOUR_KNOWN_DONT_KNOW,
        };

        $department = isset($facts->address->startAddress->citycode) ? substr($facts->address->startAddress->citycode, 0, 2) : '';

        $natures = [];
        foreach ($objects as $object) {
            if (Facts::NATURE_ROBBERY === $object->status->code) {
                $natures[] = Facts::NATURE_ROBBERY;
            } else {
                $natures[] = Facts::NATURE_DEGRADATION;
            }
        }

        $factsParsed
            ->setNatures(array_unique($natures))
            ->setDescription($facts->description)
            ->setExactPlaceUnknown(!$facts->address->addressOrRouteFactsKnown)
            ->setPlace($this->translator->trans($facts->placeNature->label))
            ->setExactDateKnown($facts->offenseDate->exactDateKnown)
            ->setStartDate($this->dateParser->parse($facts->offenseDate->startDate))
            ->setEndDate($facts->offenseDate->endDate ? $this->dateParser->parse($facts->offenseDate->endDate) : null)
            ->setStartAddress($facts->address->startAddress->label ?? '')
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
