<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Entity\Corporation;

class CorporationParser
{
    public function __construct(private readonly PhoneParser $phoneParser, private readonly AddressParser $addressParser)
    {
    }

    public function parse(object $corporation): Corporation
    {
        $corporationParsed = new Corporation();

        if ($corporation->frenchAddress) {
            $address = $this->addressParser->parseFrenchAddress($corporation->frenchAddress);
        } else {
            $address = $this->addressParser->parseForeignAddress($corporation->foreignAddress);
        }

        $corporationParsed
            ->setSiretNumber($corporation->siret)
            ->setCompanyName($corporation->name)
            ->setDeclarantPosition($corporation->function)
            ->setNationality($corporation->nationality->label)
            ->setContactEmail($corporation->email)
            ->setPhone($this->phoneParser->parse($corporation->phone))
            ->setCountry($corporation->country->label)
            ->setAddress($address->getAddress())
            ->setDepartment($address->getDepartment())
            ->setDepartmentNumber($address->getDepartmentNumber())
            ->setCity($address->getCity())
            ->setInseeCode($address->getInseeCode())
            ->setStreetNumber((int) $address->getStreetNumber())
            ->setStreetType($address->getStreetType())
            ->setStreetName($address->getStreetName())
            ->setPostCode($address->getPostCode());

        return $corporationParsed;
    }
}
