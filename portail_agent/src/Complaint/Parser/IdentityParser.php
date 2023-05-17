<?php

namespace App\Complaint\Parser;

use App\Entity\Identity;

class IdentityParser
{
    public function __construct(
        private readonly DateParser $dateParser,
        private readonly PhoneParser $phoneParser,
    ) {
    }

    public function parse(object $civilStateInput, object $contactInformationInput, ?object $declarantStatusInput = null): Identity
    {
        $identity = new Identity();

        $identity
            ->setDeclarantStatus($declarantStatusInput?->declarantStatus->code)
            ->setCivility($civilStateInput->civility->code)
            ->setFirstname($civilStateInput->firstnames)
            ->setLastname($civilStateInput->birthName)
            ->setMarriedName($civilStateInput->usageName)
            ->setBirthday($this->dateParser->parseImmutable($civilStateInput->birthDate));

        $this->parseBirthdayPlace($identity, $civilStateInput->birthLocation);
        $this->parseAddress($identity, $contactInformationInput);

        $identity->setMobilePhone(
            $this->choosePhoneNumber(
                $contactInformationInput->phone,
                $contactInformationInput->mobile,
            )
        );

        $identity
            ->setEmail($contactInformationInput->email)
            ->setNationality($civilStateInput->nationality->label)
            ->setJob($civilStateInput->job->label);

        return $identity;
    }

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

    private function parseAddress(Identity $identity, object $addressInput): void
    {
        if ($addressInput->frenchAddress) {
            $this->parseFrenchAddress($identity, $addressInput->frenchAddress);
        } else {
            $this->parseForeignAddress($identity, $addressInput->foreignAddress);
        }

        $identity->setAddressCountry($addressInput->country->label);
    }

    private function parseFrenchAddress(Identity $identity, object $addressInput): void
    {
        match ($addressInput->addressType) {
            'etalab_address' => $this->parseEtalabFrenchAddress($identity, $addressInput),
            default => $this->parseStandardFrenchAddress($identity, $addressInput),
        };
    }

    private function parseEtalabFrenchAddress(Identity $identity, object $addressInput): void
    {
        $identity
            ->setAddress($addressInput->label)
            ->setAddressStreetType('') // TODO: extract street type from street name for french address if possible
            ->setAddressStreetNumber($addressInput->houseNumber ?? '')
            ->setAddressStreetName($addressInput->street)
            ->setAddressInseeCode($addressInput->citycode)
            ->setAddressPostcode($addressInput->postcode)
            ->setAddressCity($addressInput->city);

        $context = array_map('trim', explode(',', $addressInput->context));
        $identity
            ->setAddressDepartmentNumber((int) $context[0])
            ->setAddressDepartment($context[1]);
    }

    private function parseStandardFrenchAddress(Identity $identity, object $addressInput): void
    {
        $identity->setAddress($addressInput->label)
            ->setAddressStreetType('')
            ->setAddressStreetNumber('')
            ->setAddressStreetName('')
            ->setAddressInseeCode('')
            ->setAddressPostcode('')
            ->setAddressCity('')
            ->setAddressDepartment('');
    }

    private function parseForeignAddress(Identity $identity, object $foreignAddress): void
    {
        $identity->setAddress('')
            ->setAddressStreetType('')
            ->setAddressStreetNumber($foreignAddress->houseNumber ?? '')
            ->setAddressStreetName($foreignAddress->street)
            ->setAddressInseeCode('')
            ->setAddressPostcode($foreignAddress->postcode)
            ->setAddressCity($foreignAddress->city)
            ->setAddressDepartment('');
    }

    private function choosePhoneNumber(object $fixPhone, object $mobilePhone): string
    {
        if ($fixPhone->number) {
            return $this->phoneParser->parse($fixPhone);
        }

        return $this->phoneParser->parse($mobilePhone);
    }
}
