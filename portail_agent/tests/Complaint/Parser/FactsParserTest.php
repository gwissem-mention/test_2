<?php

declare(strict_types=1);

namespace Complaint\Parser;

use App\Complaint\Parser\FactsParser;
use App\Entity\Facts;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @phpstan-import-type JsonFacts from FactsParser
 */
class FactsParserTest extends KernelTestCase
{
    private const OBJECTS_JSON = <<<JSON
{
    "objects": [
        {
            "status": {
                "code": 1,
                "label": "pel.stolen"
            },
            "category": {
                "code": 1,
                "label": "pel.object.category.documents"
            },
            "label": null,
            "brand": null,
            "model": null,
            "phoneNumberLine": null,
            "operator": null,
            "serialNumber": null,
            "description": null,
            "quantity": null,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 1500.0,
            "documentType": 1,
            "otherDocumentType": null,
            "documentOwned": false,
            "documentAdditionalInformation": {
                  "documentOwnerLastName": "DUPONT",
                  "documentOwnerFirstName": "Jean",
                  "documentOwnerCompany": "Test",
                  "documentOwnerPhone": {
                        "country": "FR",
                        "code": "33",
                        "number": null
                  },
                  "documentOwnerEmail": null,
                  "documentOwnerAddress": {
                        "addressType": "etalab_address",
                        "id": "67482_4385_00104",
                        "type": "housenumber",
                        "score": 0.8910672727272726,
                        "houseNumber": "104",
                        "street": "Rue Mélanie",
                        "name": "104 Rue Mélanie",
                        "postcode": "67000",
                        "citycode": "67482",
                        "city": "Strasbourg",
                        "district": null,
                        "context": "67, Bas-Rhin, Grand Est",
                        "x": 1053502.2,
                        "y": 6844466.66,
                        "importance": 0.80174,
                        "label": "104 Rue Mélanie 67000 Strasbourg",
                        "latitude": null,
                        "longitude": null
                  },
                  "documentNumber": null,
                  "documentIssuedBy": null,
                  "documentIssuedOn": null,
                  "documentValidityEndDate": null
            },
            "files": [
                {
                "name":"iphone.png",
                "path":"92de373d10aa4332c27ed8356b02b7a7293d9fca.png",
                "type":"image\/png",
                "size":16355
                }
            ]
        },
        {
            "status": {
                "code": 2,
                "label": "pel.degraded"
            },
            "category": {
                "code": 2,
                "label": "pel.object.category.payment.ways"
            },
            "label": "Carte bleu",
            "brand": null,
            "model": null,
            "phoneNumberLine": null,
            "operator": null,
            "serialNumber": null,
            "description": null,
            "quantity": null,
            "bank": "BNP",
            "bankAccountNumber": "4564654654654",
            "creditCardNumber": "879879879879",
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": null,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": []
        },
        {
            "status": {
                "code": 1,
                "label": "pel.stolen"
            },
            "category": {
                "code": 3,
                "label": "pel.object.category.multimedia"
            },
            "label": "PC",
            "brand": "Dell",
            "model": "Inspiron 7",
            "phoneNumberLine": null,
            "operator": null,
            "serialNumber": null,
            "description": null,
            "quantity": null,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 4000.0,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": []
        },
        {
            "status": {
                "code": 2,
                "label": "pel.degraded"
            },
            "category": {
                "code": 4,
                "label": "pel.object.category.registered.vehicle"
            },
            "label": "Voiture",
            "brand": "BMW",
            "model": "X3",
            "phoneNumberLine": null,
            "operator": null,
            "serialNumber": null,
            "description": null,
            "quantity": null,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registrationNumber": "123BG30",
            "registrationNumberCountry": "FR",
            "insuranceCompany": "AXA",
            "insuranceNumber": "123456789",
            "amount": 15000.0,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": []
        },
        {
            "status": {
                "code": 1,
                "label": "pel.stolen"
            },
            "category": {
                "code": 5,
                "label": "pel.object.category.unregistered.vehicle"
            },
            "label": "V\u00e9lo",
            "brand": null,
            "model": null,
            "phoneNumberLine": null,
            "operator": null,
            "serialNumber": null,
            "description": null,
            "quantity": null,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 560.0,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": []
        },
        {
            "status": {
                "code": 1,
                "label": "pel.stolen"
            },
            "category": {
                "code": 6,
                "label": "pel.object.category.other"
            },
            "label": "oeil d'ophidia",
            "brand": null,
            "model": null,
            "phoneNumberLine": null,
            "operator": null,
            "serialNumber": null,
            "description": "Carte de collection",
            "quantity": 1,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 650.0,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": []
        },
        {
            "status": {
                "code": 1,
                "label": "pel.stolen"
            },
            "category": {
                "code": 3,
                "label": "pel.object.category.multimedia"
            },
            "label": "iPhone 11",
            "brand": "Apple",
            "model": "Iphone 11",
            "phoneNumberLine": {
                "country": "FR",
                "code": "33",
                "number": "649956685"
            },
            "operator": "SFR",
            "serialNumber": "111222333343",
            "description": null,
            "quantity": null,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 1200.0,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": []
        }
    ]
}
JSON;

    public function getParser(): FactsParser
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var FactsParser $factsParser */
        $factsParser = $container->get(FactsParser::class);

        return $factsParser;
    }

    public function testParse(): void
    {
        $parser = $this->getParser();

        /** @var JsonFacts $factsContent */
        $factsContent = json_decode('
{
	"address":
	{
		"addressOrRouteFactsKnown": false,
		"addressAdditionalInformation": "Additional information",
		"startAddress":
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
		"endAddress":
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
		}
	},
	"offenseDate":
	{
		"exactDateKnown": false,
		"choiceHour": "maybe",
		"startDate":
		{
			"date": "2021-01-01T00:00:00+00:00",
			"timestamp": 1609459200,
			"timezone": "UTC"
		},
		"endDate":
		{
			"date": "2021-01-02T00:00:00+00:00",
			"timestamp": 1609545600,
			"timezone": "UTC"
		},
		"hour": null,
		"startHour":
		{
			"date": "2021-01-01T12:00:00+00:00",
			"timestamp": 1609502400,
			"timezone": "UTC"
		},
		"endHour":
		{
			"date": "2021-01-01T13:00:00+00:00",
			"timestamp": 1609506000,
			"timezone": "UTC"
		}
	},
	"placeNature":
	{
		"code": 1,
		"label": "pel.nature.place.home"
	},
	"victimOfViolence": true,
	"victimOfViolenceText": "Violence text",
	"description": "Description"
}', false, 512, JSON_THROW_ON_ERROR);

        $objectsContent = json_decode(self::OBJECTS_JSON, false, 512, JSON_THROW_ON_ERROR);

        $facts = $parser->parse($factsContent, $objectsContent->objects);

        $this->assertInstanceOf(Facts::class, $facts);
        $this->assertEquals('Additional information', $facts->getAddressAdditionalInformation());
        $this->assertEquals('8 Boulevard du Port 80000 Amiens', $facts->getStartAddress());
        $this->assertEquals('8 Boulevard du Port 80000 Amiens', $facts->getEndAddress());
        $this->assertFalse($facts->isExactDateKnown());
        $this->assertEquals(2, $facts->getExactHourKnown());
        $this->assertEquals('2021-01-01T00:00:00+00:00', $facts->getStartDate()?->format('c'));
        $this->assertEquals('2021-01-02T00:00:00+00:00', $facts->getEndDate()?->format('c'));
        $this->assertEquals('2021-01-01T12:00:00+00:00', $facts->getStartHour()?->format('c'));
        $this->assertEquals('2021-01-01T13:00:00+00:00', $facts->getEndHour()?->format('c'));
        $this->assertEquals([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION], $facts->getNatures());
        $this->assertTrue($facts->isVictimOfViolence());
        $this->assertEquals('Violence text', $facts->getVictimOfViolenceText());
        $this->assertEquals('Description', $facts->getDescription());
        $this->assertEquals('France', $facts->getCountry());
        $this->assertEquals('80', $facts->getDepartment());
        $this->assertEquals(80, $facts->getDepartmentNumber());
        $this->assertEquals('Domicile/Logement et dépendances', $facts->getPlace());
        $this->assertEquals('Amiens', $facts->getCity());
        $this->assertEquals('80000', $facts->getPostalCode());
        $this->assertEquals('80021', $facts->getInseeCode());
        $this->assertTrue($facts->isExactPlaceUnknown());
    }

    public function testParseNoEtalab(): void
    {
        $parser = $this->getParser();

        /** @var JsonFacts $factsContent */
        $factsContent = json_decode('
{
	"address":
	{
		"addressOrRouteFactsKnown": false,
		"addressAdditionalInformation": "Additional information",
		"startAddress":
        {
			"addressType": "address",
			"label": "8 Boulevard du Port 80000 Amiens"
		},
		"endAddress":
		{
			"addressType": "address",
			"label": "8 Boulevard du Port 80000 Amiens"
		}
	},
	"offenseDate":
	{
		"exactDateKnown": false,
		"choiceHour": "maybe",
		"startDate":
		{
			"date": "2021-01-01T00:00:00+00:00",
			"timestamp": 1609459200,
			"timezone": "UTC"
		},
		"endDate":
		{
			"date": "2021-01-02T00:00:00+00:00",
			"timestamp": 1609545600,
			"timezone": "UTC"
		},
		"hour":
		{
			"date": "2021-01-01T12:00:00+00:00",
			"timestamp": 1609502400,
			"timezone": "UTC"
		},
		"startHour": null,
		"endHour": null
	},
	"placeNature":
	{
		"code": 1,
		"label": "pel.nature.place.home"
	},
	"victimOfViolence": true,
	"victimOfViolenceText": "Violence text",
	"description": "Description"
}', false, 512, JSON_THROW_ON_ERROR);

        $objectsContent = json_decode(self::OBJECTS_JSON, false, 512, JSON_THROW_ON_ERROR);
        $facts = $parser->parse($factsContent, $objectsContent->objects);

        $this->assertInstanceOf(Facts::class, $facts);
        $this->assertEquals('Additional information', $facts->getAddressAdditionalInformation());
        $this->assertEquals('8 Boulevard du Port 80000 Amiens', $facts->getStartAddress());
        $this->assertEquals('8 Boulevard du Port 80000 Amiens', $facts->getEndAddress());
        $this->assertFalse($facts->isExactDateKnown());
        $this->assertEquals(2, $facts->getExactHourKnown());
        $this->assertEquals('2021-01-01T00:00:00+00:00', $facts->getStartDate()?->format('c'));
        $this->assertEquals('2021-01-02T00:00:00+00:00', $facts->getEndDate()?->format('c'));
        $this->assertEquals('2021-01-01T12:00:00+00:00', $facts->getStartHour()?->format('c'));
        $this->assertNull($facts->getEndHour());
        $this->assertEquals([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION], $facts->getNatures());
        $this->assertTrue($facts->isVictimOfViolence());
        $this->assertEquals('Violence text', $facts->getVictimOfViolenceText());
        $this->assertEquals('Description', $facts->getDescription());
        $this->assertEquals('France', $facts->getCountry());
        $this->assertEmpty($facts->getDepartment());
        $this->assertEquals(0, $facts->getDepartmentNumber());
        $this->assertEquals('Domicile/Logement et dépendances', $facts->getPlace());
        $this->assertEmpty($facts->getCity());
        $this->assertEmpty($facts->getPostalCode());
        $this->assertEmpty($facts->getInseeCode());
        $this->assertTrue($facts->isExactPlaceUnknown());
    }
}
