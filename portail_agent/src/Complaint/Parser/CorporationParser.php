<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Complaint\Exceptions\NoAddressException;
use App\Entity\Corporation;

/**
 * @phpstan-import-type JsonPhone from PhoneParser
 * @phpstan-import-type JsonFrenchAddress from AddressParser
 * @phpstan-import-type JsonForeignAddress from AddressParser
 *
 * @phpstan-type JsonCorporation object{
 *      siret: string,
 *      name: string,
 *      function: string,
 *      nationality: object{label: string},
 *      email: string,
 *      phone: JsonPhone,
 *      country: object{label: string},
 *      frenchAddress: JsonFrenchAddress|null,
 *      foreignAddress: JsonForeignAddress|null,
 *      sameAddress: bool|null
 *   }
 */
class CorporationParser
{
    public function __construct(private readonly PhoneParser $phoneParser, private readonly AddressParser $addressParser)
    {
    }

    /**
     * @param JsonCorporation $corporation
     *
     * @throws NoAddressException
     */
    public function parse(object $corporation): Corporation
    {
        $corporationParsed = new Corporation();
        $address = null;
        if ($corporation->frenchAddress) {
            $address = $this->addressParser->parseFrenchAddress($corporation->frenchAddress);
        } elseif ($corporation->foreignAddress) {
            $address = $this->addressParser->parseForeignAddress($corporation->foreignAddress);
        }

        if (null === $address) {
            throw new NoAddressException('No address found for corporation');
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
            ->setPostCode($address->getPostCode())
            ->setSameAddressAsDeclarant($corporation->sameAddress ?? false);

        return $corporationParsed;
    }
}
