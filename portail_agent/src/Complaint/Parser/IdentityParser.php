<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Complaint\Exceptions\NoAddressException;
use App\Entity\Identity;
use App\Referential\Repository\JobRepository;

/**
 * @phpstan-import-type JsonDate from DateParser
 * @phpstan-import-type JsonFrenchAddress from AddressParser
 * @phpstan-import-type JsonForeignAddress from AddressParser
 *
 * @phpstan-type JsonCivilState object{
 *      civility: object{code: int},
 *      firstnames: string,
 *      birthName: string,
 *      usageName: string,
 *      familySituation: object{label: string},
 *      birthDate: JsonDate,
 *      birthLocation: object{
 *           frenchTown: object{
 *               label: string,
 *               departmentLabel: string,
 *               departmentCode: string,
 *               inseeCode: string,
 *               postalCode: string,
 *           }|null,
 *           otherTown: string|null,
 *           country: object{label: string},
 *      },
 *      job: object{code: string, label: string},
 *      nationality: object{label: string},
 *  }
 * @phpstan-type JsonContactInformation object{
 *       phone: object{code: string, number: string}|null,
 *       mobile: object{code: string, number: string}|null,
 *       email: string,
 *       frenchAddress: JsonFrenchAddress|null,
 *       foreignAddress: JsonForeignAddress|null,
 *       country: object{label: string},
 *   }
 * @phpstan-type JsonDeclarantStatus object{code: int}|null
 */
class IdentityParser
{
    public function __construct(
        private readonly DateParser $dateParser,
        private readonly PhoneParser $phoneParser,
        private readonly AddressParser $addressParser,
        private readonly JobRepository $jobRepository
    ) {
    }

    /**
     * @param JsonCivilState           $civilStateInput
     * @param JsonContactInformation   $contactInformationInput
     * @param JsonDeclarantStatus|null $declarantStatusInput
     *
     * @throws NoAddressException
     */
    public function parse(object $civilStateInput, object $contactInformationInput, object $declarantStatusInput = null): Identity
    {
        $identity = new Identity();

        $identity
            ->setDeclarantStatus($declarantStatusInput?->code)
            ->setCivility($civilStateInput->civility->code)
            ->setFirstname($civilStateInput->firstnames)
            ->setLastname($civilStateInput->birthName)
            ->setMarriedName($civilStateInput->usageName)
            ->setFamilySituation($civilStateInput->familySituation->label)
            ->setBirthday($this->dateParser->parseImmutable($civilStateInput->birthDate));

        $this->parseBirthdayPlace($identity, $civilStateInput->birthLocation);
        $this->parseAddress($identity, $contactInformationInput);
        $this->parseJob($identity, $civilStateInput->job);

        if ($contactInformationInput->phone) {
            $identity->setHomePhone($this->phoneParser->parse($contactInformationInput->phone));
        }

        if ($contactInformationInput->mobile) {
            $identity->setMobilePhone($this->phoneParser->parse($contactInformationInput->mobile));
        }

        $identity
            ->setEmail($contactInformationInput->email)
            ->setNationality($civilStateInput->nationality->label);

        return $identity;
    }

    /**
     * @param object{
     *           frenchTown: object{
     *               label: string,
     *               departmentLabel: string,
     *               departmentCode: string,
     *               inseeCode: string,
     *               postalCode: string,
     *           }|null,
     *           otherTown: string|null,
     *           country: object{label: string},
     *      } $birthDayPlaceInput
     */
    private function parseBirthdayPlace(Identity $identity, object $birthDayPlaceInput): void
    {
        $identity
            ->setBirthCity($birthDayPlaceInput->frenchTown->label ?? $birthDayPlaceInput->otherTown)
            ->setBirthDepartment($birthDayPlaceInput->frenchTown?->departmentLabel ?? '')
            ->setBirthDepartmentNumber((int) $birthDayPlaceInput->frenchTown?->departmentCode)
            ->setBirthInseeCode($birthDayPlaceInput->frenchTown?->inseeCode ?? '')
            ->setBirthPostalCode($birthDayPlaceInput->frenchTown?->postalCode ?? '')
            ->setBirthCountry($birthDayPlaceInput->country->label);
    }

    /**
     * @param object{
     *      frenchAddress: JsonFrenchAddress|null,
     *      foreignAddress: JsonForeignAddress|null,
     *      country: object{label: string},
     * } $addressInput
     *
     * @throws NoAddressException
     */
    private function parseAddress(Identity $identity, object $addressInput): void
    {
        $address = null;
        if ($addressInput->frenchAddress) {
            $address = $this->addressParser->parseFrenchAddress($addressInput->frenchAddress);
        } elseif ($addressInput->foreignAddress) {
            $address = $this->addressParser->parseForeignAddress($addressInput->foreignAddress);
        }

        if (null === $address) {
            throw new NoAddressException('No address found for corporation');
        }

        $identity
            ->setAddressCountry($addressInput->country->label)
            ->setAddress($address->getAddress())
            ->setAddressStreetType($address->getStreetType())
            ->setAddressStreetNumber($address->getStreetNumber())
            ->setAddressStreetName($address->getStreetName())
            ->setAddressInseeCode($address->getInseeCode())
            ->setAddressPostcode($address->getPostcode())
            ->setAddressCity($address->getCity())
            ->setAddressDepartment($address->getDepartment())
            ->setAddressDepartmentNumber($address->getDepartmentNumber());
    }

    /**
     * @param object{code: string, label: string} $jobInput
     */
    private function parseJob(Identity $identity, object $jobInput): void
    {
        $job = $this->jobRepository->findFromInsee($jobInput->code, $jobInput->label);

        $identity
            ->setJob($jobInput->label)
            ->setJobThesaurus($job->getLabelThesaurus());
    }
}
