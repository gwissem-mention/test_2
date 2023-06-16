<?php

namespace App\Complaint\Parser;

use App\Entity\Identity;

class IdentityParser
{
    public function __construct(
        private readonly DateParser $dateParser,
        private readonly PhoneParser $phoneParser,
        private readonly AddressParser $addressParser
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
            ->setFamilySituation($civilStateInput->familySituation->label)
            ->setBirthday($this->dateParser->parseImmutable($civilStateInput->birthDate));

        $this->parseBirthdayPlace($identity, $civilStateInput->birthLocation);
        $this->parseAddress($identity, $contactInformationInput);

        if ($contactInformationInput->phone) {
            $identity->setHomePhone($this->phoneParser->parse($contactInformationInput->phone));
        }

        if ($contactInformationInput->mobile) {
            $identity->setMobilePhone($this->phoneParser->parse($contactInformationInput->mobile));
        }

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
            $address = $this->addressParser->parseFrenchAddress($addressInput->frenchAddress);
        } else {
            $address = $this->addressParser->parseForeignAddress($addressInput->foreignAddress);
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
}
