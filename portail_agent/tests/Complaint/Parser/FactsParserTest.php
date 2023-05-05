<?php

namespace Complaint\Parser;

use App\Complaint\Parser\FactsParser;
use App\Entity\Facts;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FactsParserTest extends KernelTestCase
{
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

        $facts = $parser->parse($factsContent);

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
        $this->assertEquals([Facts::NATURE_ROBBERY], $facts->getNatures());
        $this->assertTrue($facts->isVictimOfViolence());
        $this->assertEquals('Violence text', $facts->getVictimOfViolenceText());
        $this->assertEquals('Description', $facts->getDescription());
        $this->assertEquals('France', $facts->getCountry());
        $this->assertEquals('80', $facts->getDepartment());
        $this->assertEquals(80, $facts->getDepartmentNumber());
        $this->assertEquals('Domicile/Logement', $facts->getPlace());
        $this->assertEquals('Amiens', $facts->getCity());
        $this->assertEquals('80000', $facts->getPostalCode());
        $this->assertEquals('80021', $facts->getInseeCode());
        $this->assertTrue($facts->isExactPlaceUnknown());
    }

    public function testParseNoEtalab(): void
    {
        $parser = $this->getParser();

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

        $facts = $parser->parse($factsContent);

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
        $this->assertEquals([Facts::NATURE_ROBBERY], $facts->getNatures());
        $this->assertTrue($facts->isVictimOfViolence());
        $this->assertEquals('Violence text', $facts->getVictimOfViolenceText());
        $this->assertEquals('Description', $facts->getDescription());
        $this->assertEquals('France', $facts->getCountry());
        $this->assertEmpty($facts->getDepartment());
        $this->assertEquals(0, $facts->getDepartmentNumber());
        $this->assertEquals('Domicile/Logement', $facts->getPlace());
        $this->assertEmpty($facts->getCity());
        $this->assertEmpty($facts->getPostalCode());
        $this->assertEmpty($facts->getInseeCode());
        $this->assertTrue($facts->isExactPlaceUnknown());
    }
}
