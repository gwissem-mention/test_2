<?php

declare(strict_types=1);

namespace App\Form\Factory;

use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\Address\AddressModel;

class AddressModelFactory
{
    public static function create(string $label, string $latitude = null, string $longitude = null): AddressModel
    {
        return (new AddressModel())->setLabel($label)->setLongitude($longitude)->setLatitude($latitude);
    }

    public static function createFromEtalab(
        string $label = null,
        string $id = null,
        string $type = null,
        float $score = null,
        string $houseNumber = null,
        string $street = null,
        string $name = null,
        string $postcode = null,
        string $citycode = null,
        string $city = null,
        string $district = null,
        string $context = null,
        float $x = null,
        float $y = null,
        float $importance = null,
        string $latitude = null,
        string $longitude = null
    ): AddressEtalabModel {
        return (new AddressEtalabModel())
            ->setLabel($label)
            ->setId($id)
            ->setType($type)
            ->setScore($score)
            ->setHouseNumber($houseNumber)
            ->setStreet($street)
            ->setName($name)
            ->setPostcode($postcode)
            ->setCitycode($citycode)
            ->setCity($city)
            ->setDistrict($district)
            ->setContext($context)
            ->setX($x)
            ->setY($y)
            ->setImportance($importance)
            ->setLatitude($latitude)
            ->setLongitude($longitude);
    }
}
