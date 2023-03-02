<?php

declare(strict_types=1);

namespace App\Form\Model\Address;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'addressType', mapping: [
    'address' => AddressModel::class,
    'etalab_address' => AddressEtalabModel::class,
])]
abstract class AbstractSerializableAddress implements AddressInterface
{
    protected string $addressType;
}
