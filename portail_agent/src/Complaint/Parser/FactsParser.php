<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Entity\Facts;

/**
 * @phpstan-import-type JsonDate from DateParser
 *
 * @phpstan-type JsonFacts object{
 *      description: string,
 *      offenseDate: object,
 *      placeNature: string,
 *      address: object{
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
 *           hour: JsonDate|null,
 *      },
 *      callingPhone: object{code: string, number: string}|null,
 *      website: string|null,
 *  }
 */
class FactsParser
{
    public function __construct(private readonly DateParser $dateParser, private readonly PhoneParser $phoneParser)
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

        $startAddressDepartment = isset($facts->address->startAddress->citycode) ? substr($facts->address->startAddress->citycode, 0, 2) : '';
        $endAddressDepartment = isset($facts->address->endAddress->citycode) ? substr($facts->address->endAddress->citycode, 0, 2) : null;

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
            ->setPlace($facts->placeNature)
            ->setExactDateKnown($facts->offenseDate->exactDateKnown)
            ->setStartDate($this->dateParser->parse($facts->offenseDate->startDate))
            ->setEndDate($facts->offenseDate->endDate ? $this->dateParser->parse($facts->offenseDate->endDate) : null)
            ->setStartAddress($facts->address->startAddress->label ?? '')
            ->setEndAddress($facts->address->endAddress->label ?? null)
            ->setStartAddressCountry('France')
            ->setStartAddressDepartment($startAddressDepartment)
            ->setStartAddressDepartmentNumber((int) $startAddressDepartment)
            ->setStartAddressCity($facts->address->startAddress->city ?? '')
            ->setStartAddressPostalCode($facts->address->startAddress->postcode ?? '')
            ->setStartAddressInseeCode($facts->address->startAddress->citycode ?? '')
            ->setEndAddressCountry(null !== $facts->address->endAddress ? 'France' : null)
            ->setEndAddressDepartment($endAddressDepartment)
            ->setEndAddressDepartmentNumber($endAddressDepartment ? (int) $endAddressDepartment : null)
            ->setEndAddressCity($facts->address->endAddress->city ?? null)
            ->setEndAddressPostalCode($facts->address->endAddress->postcode ?? null)
            ->setEndAddressInseeCode($facts->address->endAddress->citycode ?? null)
            ->setExactHourKnown($exactHourKnown)
            ->setStartHour(null !== ($startHour = $facts->offenseDate->hour ?? $facts->offenseDate->startHour) ? $this->dateParser->parse($startHour) : null)
            ->setEndHour($facts->offenseDate->endHour ? $this->dateParser->parse($facts->offenseDate->endHour) : null)
            ->setAddressAdditionalInformation($facts->address->addressAdditionalInformation ?? null)
            ->setVictimOfViolence($facts->victimOfViolence ?? null)
            ->setVictimOfViolenceText($facts->victimOfViolenceText ?? null)
            ->setCallingPhone($facts->callingPhone ? $this->phoneParser->parse($facts->callingPhone) : null)
            ->setWebsite($facts->website ?? null);

        return $factsParsed;
    }
}
