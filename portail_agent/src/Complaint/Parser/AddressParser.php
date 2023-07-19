<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Complaint\DTO\ParserAddressDTO;

class AddressParser
{
    public function parseFrenchAddress(object $address): ParserAddressDTO
    {
        if ('etalab_address' === $address->addressType) {
            $context = array_map('trim', explode(',', $address->context));
            $departmentNumber = (int) $context[0];
            $department = (string) $context[1];

            return new ParserAddressDTO(
                $address->label ?? '',
                $address->city ?? '',
                $address->postcode ?? '',
                $address->citycode ?? '',
                $address->houseNumber ?? '',
                '', // TODO: extract street type from street name for french// address if possible
                $address->street ?? '',
                $department,
                $departmentNumber
            );
        }

        return new ParserAddressDTO($address->label ?? '');
    }

    public function parseForeignAddress(object $address): ParserAddressDTO
    {
        $addressConcat = $address->houseNumber.' '.$address->type.' '.$address->street.' '.$address->apartment
            .' '.$address->city.' '.$address->context.' '.$address->postcode;

        return new ParserAddressDTO(
            $addressConcat,
            $address->city ?? '',
            $address->postcode ?? '',
            '',
            $address->houseNumber ?? '',
            $address->type ?? '',
            $address->street ?? ''
        );
    }
}
