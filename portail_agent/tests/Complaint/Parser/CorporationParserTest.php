<?php

declare(strict_types=1);

namespace App\Tests\Complaint\Parser;

use App\Complaint\Parser\CorporationParser;
use App\Entity\Corporation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CorporationParserTest extends KernelTestCase
{
    public function getParser(): CorporationParser
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var CorporationParser $parser */
        $parser = $container->get(CorporationParser::class);

        return $parser;
    }

    public function testParseFrenchAddress(): void
    {
        $parser = $this->getParser();

        $corporationContent = json_decode('
{
	"corporation":
	{
        "siret": "12345678900000",
		"name": "Entreprise",
		"function": "CEO",
		"nationality":
		{
            "code": "23",
			"label": "FRANCAISE"
		},
		"phone":
		{
            "country": "FR",
			"code": "33",
			"number": "0601020304"
		},
		"country":
		{
            "inseeCode": 99160,
			"label": "France"
		},
		"frenchAddress":
		{
            "addressType": "etalab_address",
			"id": "80021_6590_00008",
			"type": "housenumber",
			"score": 0.49219200956938,
			"houseNumber": "8",
			"street": "Boulevard du Port",
			"name": "8 Boulevard du Port",
			"postcode": "80000",
			"citycode": "80021",
			"city": "Amiens",
			"district": null,
			"context": "80, Somme, Hauts-de-France",
			"x": 648952.58,
			"y": 6977867.14,
			"importance": 0.67727,
			"label": "8 Boulevard du Port 80000 Amiens",
			"latitude": null,
			"longitude": null
		},
		"foreignAddress": null,
		"email": "entreprise@gmail.com"
	}
}', false, 512, JSON_THROW_ON_ERROR);

        $corporation = $parser->parse($corporationContent->corporation);

        $this->assertInstanceOf(Corporation::class, $corporation);
        $this->assertEquals('12345678900000', $corporation->getSiretNumber());
        $this->assertEquals('Entreprise', $corporation->getCompanyName());
        $this->assertEquals('CEO', $corporation->getDeclarantPosition());
        $this->assertEquals('FRANCAISE', $corporation->getNationality());
        $this->assertEquals('entreprise@gmail.com', $corporation->getContactEmail());
        $this->assertEquals('+33 0601020304', $corporation->getPhone());
        $this->assertEquals('France', $corporation->getCountry());
        $this->assertEquals('8 Boulevard du Port 80000 Amiens', $corporation->getAddress());
        $this->assertEquals('Somme', $corporation->getDepartment());
        $this->assertEquals(80, $corporation->getDepartmentNumber());
        $this->assertEquals('Amiens', $corporation->getCity());
        $this->assertEquals('80021', $corporation->getInseeCode());
        $this->assertEquals('8', $corporation->getStreetNumber());
        $this->assertEquals('', $corporation->getStreetType());
        $this->assertEquals('Boulevard du Port', $corporation->getStreetName());
        $this->assertEquals('80000', $corporation->getPostCode());
    }

    public function testParseForeignAddress(): void
    {
        $parser = $this->getParser();

        $corporationContent = json_decode('
	{
        "siret": "12345678900000",
		"name": "Entreprise",
		"function": "CEO",
		"nationality":
		{
            "code": "23",
			"label": "FRANCAISE"
		},
		"phone":
		{
            "country": "FR",
			"code": "33",
			"number": "0601020304"
		},
		"country":
		{
            "inseeCode": 99134,
			"label": "Espagne"
		},
		"foreignAddress":
		{
            "addressType": "etalab_address",
            "apartment": "2",
            "id": null,
            "type": "Av.",
            "score": null,
            "houseNumber": "134",
            "street": "Roque Nublo",
            "name": null,
            "postcode": "28223",
            "citycode": null,
            "city": "Madrid",
            "district": null,
            "context": "Pozuelo de Alarc\u00f3n",
            "x": null,
            "y": null,
            "importance": null,
            "label": null,
            "latitude": null,
            "longitude": null
        },
		"frenchAddress": null,
		"email": null
	}', false, 512, JSON_THROW_ON_ERROR);

        $corporation = $parser->parse($corporationContent);

        $this->assertInstanceOf(Corporation::class, $corporation);
        $this->assertEquals('12345678900000', $corporation->getSiretNumber());
        $this->assertEquals('Entreprise', $corporation->getCompanyName());
        $this->assertEquals('CEO', $corporation->getDeclarantPosition());
        $this->assertEquals('FRANCAISE', $corporation->getNationality());
        $this->assertEquals('+33 0601020304', $corporation->getPhone());
        $this->assertEquals('Espagne', $corporation->getCountry());
        $this->assertEquals('134 Av. Roque Nublo 2 Madrid Pozuelo de Alarcón 28223', $corporation->getAddress());
        $this->assertEquals('', $corporation->getDepartment());
        $this->assertEquals(0, $corporation->getDepartmentNumber());
        $this->assertEquals('Madrid', $corporation->getCity());
        $this->assertEquals('', $corporation->getInseeCode());
        $this->assertEquals('134', $corporation->getStreetNumber());
        $this->assertEquals('Av.', $corporation->getStreetType());
        $this->assertEquals('Roque Nublo', $corporation->getStreetName());
        $this->assertEquals('28223', $corporation->getPostCode());
        $this->assertNull($corporation->getContactEmail());
    }

    public function testParseSimpleAddress(): void
    {
        $parser = $this->getParser();

        $corporationContent = json_decode('
	{
        "siret": "12345678900000",
		"name": "Entreprise",
		"function": "CEO",
		"nationality":
		{
            "code": "23",
			"label": "FRANCAISE"
		},
		"phone":
		{
            "country": "FR",
			"code": "33",
			"number": "0601020304"
		},
		"country":
		{
            "inseeCode": 99134,
			"label": "Espagne"
		},
		"frenchAddress":
		{
		   "addressType": "address",
           "label": "Avenue de la république Paris"
        },
		"foreignAddress": null,
		"email": null
	}', false, 512, JSON_THROW_ON_ERROR);

        $corporation = $parser->parse($corporationContent);

        $this->assertInstanceOf(Corporation::class, $corporation);
        $this->assertEquals('12345678900000', $corporation->getSiretNumber());
        $this->assertEquals('Entreprise', $corporation->getCompanyName());
        $this->assertEquals('CEO', $corporation->getDeclarantPosition());
        $this->assertEquals('FRANCAISE', $corporation->getNationality());
        $this->assertEquals('+33 0601020304', $corporation->getPhone());
        $this->assertEquals('Espagne', $corporation->getCountry());
        $this->assertEquals('Avenue de la république Paris', $corporation->getAddress());
        $this->assertEmpty($corporation->getDepartment());
        $this->assertEquals(0, $corporation->getDepartmentNumber());
        $this->assertEmpty($corporation->getCity());
        $this->assertEmpty($corporation->getInseeCode());
        $this->assertEmpty($corporation->getStreetNumber());
        $this->assertEmpty($corporation->getStreetType());
        $this->assertEmpty($corporation->getStreetName());
        $this->assertEmpty($corporation->getPostCode());
        $this->assertNull($corporation->getContactEmail());
    }
}
