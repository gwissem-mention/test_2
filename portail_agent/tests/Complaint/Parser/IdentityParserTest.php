<?php

declare(strict_types=1);

namespace App\Tests\Complaint\Parser;

use App\Complaint\Parser\AddressParser;
use App\Complaint\Parser\DateParser;
use App\Complaint\Parser\IdentityParser;
use App\Complaint\Parser\PhoneParser;
use App\Entity\Identity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IdentityParserTest extends KernelTestCase
{
    private const IDENTITY_JSON = <<<JSON
{
    "declarantStatus": {
      "code": 1,
      "label": "pel.complaint.identity.victim"
    },
    "civilState": {
      "civility": {
        "code": 2,
        "label": "pel.mme"
      },
      "birthName": "DUBOIS",
      "usageName": null,
      "firstnames": "Angela Claire Louise",
      "familySituation": {
        "code": 1,
        "label": "Célibataire"
      },
      "birthDate": {
        "date": "1962-08-24T00:00:00+00:00",
        "timestamp": -232156800,
        "timezone": "+00:00"
      },
      "birthLocation": {
        "country": {
          "inseeCode": 99160,
          "label": "France"
        },
        "frenchTown": {
          "inseeCode": "75107",
          "label": "Paris 7e arrondissement",
          "postalCode": "75007",
          "departmentCode": "75",
          "departmentLabel": "Paris"
        },
        "otherTown": null
      },
      "nationality": {
        "code": "23",
        "label": "FRANCAISE"
      },
      "job": {
        "code": "4699",
        "label": "Professeure vacataire en lyc\u00e9e"
      }
    },
    "contactInformation": {
      "phone": {
        "country": "FR",
        "code": "33",
        "number": null
      },
      "mobile": {
        "country": "FR",
        "code": "33",
        "number": "612389328"
      },
      "country": {
        "inseeCode": 99160,
        "label": "France"
      },
      "frenchAddress": {
        "addressType": "etalab_address",
        "id": "71149_0110_00001",
        "type": "housenumber",
        "score": 0.9486418181818181,
        "houseNumber": "1",
        "street": "Route de Dracy",
        "name": "1 Route de Dracy",
        "postcode": "71490",
        "citycode": "71149",
        "city": "Couches",
        "district": null,
        "context": "71, Sa\u00f4ne-et-Loire, Bourgogne-Franche-Comt\u00e9",
        "x": 820100.03,
        "y": 6642242.04,
        "importance": 0.43506,
        "label": "1 Route de Dracy 71490 Couches",
        "latitude": null,
        "longitude": null
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
      "email": "wossewodda-3728@yopmail.com"
    },
    "representedPersonCivilState": {
      "civility": {
        "code": 2,
        "label": "pel.mme"
      },
      "birthName": "DUBOIS",
      "usageName": null,
      "firstnames": "Angela Claire Louise",
      "familySituation": {
        "code": 1,
        "label": "Célibataire"
      },
      "birthDate": {
        "date": "1962-08-24T00:00:00+00:00",
        "timestamp": -232156800,
        "timezone": "+00:00"
      },
      "birthLocation": {
        "country": {
          "inseeCode": 99160,
          "label": "France"
        },
        "frenchTown": {
          "inseeCode": "75107",
          "label": "Paris 7e arrondissement",
          "postalCode": "75007",
          "departmentCode": "75",
          "departmentLabel": "Paris"
        },
        "otherTown": null
      },
      "nationality": {
        "code": "23",
        "label": "FRANCAISE"
      },
      "job": {
        "code": "4699",
        "label": "Professeure vacataire en lyc\u00e9e"
      }
    },
    "representedPersonContactInformation": {
      "phone": {
        "country": "FR",
        "code": "33",
        "number": null
      },
      "mobile": {
        "country": "FR",
        "code": "33",
        "number": "612389328"
      },
      "country": {
        "inseeCode": 99160,
        "label": "France"
      },
      "frenchAddress": {
        "addressType": "etalab_address",
        "id": "71149_0110_00001",
        "type": "housenumber",
        "score": 0.9486418181818181,
        "houseNumber": "1",
        "street": "Route de Dracy",
        "name": "1 Route de Dracy",
        "postcode": "71490",
        "citycode": "71149",
        "city": "Couches",
        "district": null,
        "context": "71, Sa\u00f4ne-et-Loire, Bourgogne-Franche-Comt\u00e9",
        "x": 820100.03,
        "y": 6642242.04,
        "importance": 0.43506,
        "label": "1 Route de Dracy 71490 Couches",
        "latitude": null,
        "longitude": null
      },
      "foreignAddress": null,
      "email": "wossewodda-3728@yopmail.com"
    },
    "corporation": null
  }
JSON;

    public function getParser(): IdentityParser
    {
        return new IdentityParser(new DateParser(), new PhoneParser(), new AddressParser());
    }

    public function testParse(): void
    {
        $parser = $this->getParser();

        $identityParsed = json_decode(self::IDENTITY_JSON);

        $identity = $parser->parse($identityParsed->civilState, $identityParsed->contactInformation, $identityParsed->declarantStatus);

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertSame(1, $identity->getDeclarantStatus());
        $this->assertSame(2, $identity->getCivility());
        $this->assertSame('Angela Claire Louise', $identity->getFirstname());
        $this->assertSame('DUBOIS', $identity->getLastname());
        $this->assertNull($identity->getMarriedName());
        $this->assertSame('1962-08-24T00:00:00+00:00', $identity->getBirthday()?->format('c'));
        $this->assertSame('Paris 7e arrondissement', $identity->getBirthCity());

        $this->assertSame('75007', $identity->getBirthPostalCode());

        $this->assertSame('75107', $identity->getBirthInseeCode());
        $this->assertSame('France', $identity->getBirthCountry());

        $this->assertSame('Paris', $identity->getBirthDepartment());
        $this->assertSame(75, $identity->getBirthDepartmentNumber());

        $this->assertSame('1 Route de Dracy 71490 Couches', $identity->getAddress());
        $this->assertSame('1', $identity->getAddressStreetNumber());

        // @TODO Find how to fetch that
        // $this->assertSame('1', $identity->getAddressStreetType());

        $this->assertSame('Route de Dracy', $identity->getAddressStreetName());
        $this->assertSame('71149', $identity->getAddressInseeCode());
        $this->assertSame('71490', $identity->getAddressPostcode());

        // @TODO Find how to fetch that
        // $this->assertSame('71490', $identity->getAddressCountry());

        $this->assertSame('Saône-et-Loire', $identity->getAddressDepartment());
        $this->assertSame(71, $identity->getAddressDepartmentNumber());

        $this->assertSame('+33 612389328', $identity->getMobilePhone());
        $this->assertSame('wossewodda-3728@yopmail.com', $identity->getEmail());

        $this->assertSame('FRANCAISE', $identity->getNationality());
        $this->assertSame('Professeure vacataire en lycée', $identity->getJob());
    }

    public function testParseBirthdayOtherTown(): void
    {
        $parser = $this->getParser();

        $identityParsed = json_decode(self::IDENTITY_JSON);

        $identityParsed->civilState->birthLocation->frenchTown = null;
        $identityParsed->civilState->birthLocation->otherTown = 'New York';

        $identity = $parser->parse($identityParsed->civilState, $identityParsed->contactInformation, $identityParsed->declarantStatus);

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertSame('New York', $identity->getBirthCity());
        $this->assertEmpty($identity->getBirthInseeCode());
    }

    public function testParseForeignAddress(): void
    {
        $parser = $this->getParser();

        $identityParsed = json_decode(self::IDENTITY_JSON);

        $identityParsed->contactInformation->frenchAddress = null;

        $identity = $parser->parse($identityParsed->civilState, $identityParsed->contactInformation, $identityParsed->declarantStatus);

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertEquals('134 Av. Roque Nublo 2 Madrid Pozuelo de Alarcón 28223', $identity->getAddress());
        $this->assertEquals('', $identity->getAddressDepartment());
        $this->assertEquals(0, $identity->getAddressDepartmentNumber());
        $this->assertEquals('Madrid', $identity->getAddressCity());
        $this->assertEquals('', $identity->getAddressInseeCode());
        $this->assertEquals('134', $identity->getAddressStreetNumber());
        $this->assertEquals('Av.', $identity->getAddressStreetType());
        $this->assertEquals('Roque Nublo', $identity->getAddressStreetName());
        $this->assertEquals('28223', $identity->getAddressPostcode());
    }

    public function testParseReprensentedPerson(): void
    {
        $parser = $this->getParser();

        $identityParsed = json_decode(self::IDENTITY_JSON);

        $identity = $parser->parse($identityParsed->representedPersonCivilState, $identityParsed->representedPersonContactInformation, $identityParsed->declarantStatus);

        $this->assertInstanceOf(Identity::class, $identity);

        $this->assertSame(1, $identity->getDeclarantStatus());
        $this->assertSame(2, $identity->getCivility());
        $this->assertSame('Angela Claire Louise', $identity->getFirstname());
        $this->assertSame('DUBOIS', $identity->getLastname());
        $this->assertNull($identity->getMarriedName());
        $this->assertSame('1962-08-24T00:00:00+00:00', $identity->getBirthday()?->format('c'));
        $this->assertSame('Paris 7e arrondissement', $identity->getBirthCity());

        $this->assertSame('75007', $identity->getBirthPostalCode());

        $this->assertSame('75107', $identity->getBirthInseeCode());
        $this->assertSame('France', $identity->getBirthCountry());

        $this->assertSame('Paris', $identity->getBirthDepartment());
        $this->assertSame(75, $identity->getBirthDepartmentNumber());

        $this->assertSame('1 Route de Dracy 71490 Couches', $identity->getAddress());
        $this->assertSame('1', $identity->getAddressStreetNumber());

        // @TODO Find how to fetch that
        // $this->assertSame('1', $identity->getAddressStreetType());

        $this->assertSame('Route de Dracy', $identity->getAddressStreetName());
        $this->assertSame('71149', $identity->getAddressInseeCode());
        $this->assertSame('71490', $identity->getAddressPostcode());

        // @TODO Find how to fetch that
        // $this->assertSame('71490', $identity->getAddressCountry());

        $this->assertSame('Saône-et-Loire', $identity->getAddressDepartment());
        $this->assertSame(71, $identity->getAddressDepartmentNumber());

        $this->assertSame('+33 612389328', $identity->getMobilePhone());
        $this->assertSame('wossewodda-3728@yopmail.com', $identity->getEmail());

        $this->assertSame('FRANCAISE', $identity->getNationality());
        $this->assertSame('Professeure vacataire en lycée', $identity->getJob());
    }
}
