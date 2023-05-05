<?php

namespace App\Complaint\Parser;

use App\Entity\Corporation;
use Symfony\Contracts\Translation\TranslatorInterface;

class CorporationParser
{
    public function __construct(private readonly TranslatorInterface $translator, private readonly PhoneParser $phoneParser)
    {
    }

    public function parse(object $corporation): Corporation
    {
        $corporationParsed = new Corporation();

        $departmentNumber = 0;
        $city = $department = $address = $cityCode = $streetNumber = $streetName = $postCode = $streetType = '';
        if ($corporation->frenchAddress) {
            $address = $corporation->frenchAddress->label;

            if ('etalab_address' === $corporation->frenchAddress->addressType) {
                $city = $corporation->frenchAddress->city;
                $cityCode = $corporation->frenchAddress->citycode;
                $streetNumber = $corporation->frenchAddress->houseNumber;
                $streetName = $corporation->frenchAddress->street;
                $postCode = $corporation->frenchAddress->postcode;
                $context = array_map('trim', explode(',', $corporation->frenchAddress->context));

                $departmentNumber = (int) $context[0];
                $department = $context[1];
            }
        } elseif ($corporation->foreignAddress) {
            $address = $corporation->foreignAddress->houseNumber
                .' '.$corporation->foreignAddress->type
                .' '.$corporation->foreignAddress->street
                .' '.$corporation->foreignAddress->apartment
                .' '.$corporation->foreignAddress->city
                .' '.$corporation->foreignAddress->context
                .' '.$corporation->foreignAddress->postcode;

            $city = $corporation->foreignAddress->city;
            $streetNumber = $corporation->foreignAddress->houseNumber;
            $streetName = $corporation->foreignAddress->street;
            $postCode = $corporation->foreignAddress->postcode;
            $streetType = $corporation->foreignAddress->type;
        }

        $corporationParsed
            ->setSirenNumber($corporation->siren)
            ->setCompanyName($corporation->name)
            ->setDeclarantPosition($corporation->function)
            ->setNationality($this->translator->trans($corporation->nationality->label))
            ->setContactEmail($corporation->email)
            ->setPhone($this->phoneParser->parse($corporation->phone))
            ->setCountry($corporation->country->label)
            ->setAddress($address)
            ->setDepartment($department)
            ->setDepartmentNumber($departmentNumber)
            ->setCity($city)
            ->setInseeCode($cityCode)
            ->setStreetNumber((int) $streetNumber)
            ->setStreetType($streetType) // TODO: extract street type from street name for french address if possible
            ->setStreetName($streetName)
            ->setPostCode($postCode);

        return $corporationParsed;
    }
}
