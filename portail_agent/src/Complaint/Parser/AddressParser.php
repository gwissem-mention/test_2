<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Complaint\DTO\ParserAddressDTO;

/**
 * @phpstan-type JsonFrenchAddress object{
 *      addressType: string,
 *      label: string|null,
 *      city: string|null,
 *      postcode: string|null,
 *      citycode: string|null,
 *      houseNumber: string|null,
 *      street: string|null,
 *      context: string|null,
 * }
 * @phpstan-type JsonForeignAddress object{
 *      city: string|null,
 *      postcode: string|null,
 *      houseNumber: string|null,
 *      street: string|null,
 *      context: string|null,
 *      apartment: string|null,
 *      type: string|null,
 *   }
 */
class AddressParser
{
    /**
     * @param JsonFrenchAddress $address
     */
    public function parseFrenchAddress(object $address): ParserAddressDTO
    {
        if ('etalab_address' === $address->addressType) {
            $context = array_map('trim', explode(',', (string) $address->context));
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

    /**
     * @param object{
     *     city: string|null,
     *     postcode: string|null,
     *     houseNumber: string|null,
     *     street: string|null,
     *     context: string|null,
     *     apartment: string|null,
     *     type: string|null,
     *  } $address
     */
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
