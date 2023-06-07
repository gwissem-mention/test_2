<?php

namespace App\Tests\Complaint\Parser;

use App\Complaint\Parser\DateParser;
use App\Complaint\Parser\IdentityParser;
use App\Complaint\Parser\PhoneParser;
use App\Entity\Identity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IdentityParserTest extends KernelTestCase
{
    private const IDENTITY_JSON = <<<JSON
{
    "civilState": {
      "civility": {
        "code": 2,
        "label": "pel.mme"
      },
      "birthName": "DUBOIS",
      "usageName": null,
      "firstnames": "Angela Claire Louise",
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
      "foreignAddress": {
        "addressType": "foreign_address",
        "type": "housenumber",
        "houseNumber": "1",
        "street": "Route de Dracy",
        "postcode": "71490",
        "city": "Couches"
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
        // self::bootKernel();
        // $container = static::getContainer();
        //
        // return $container->get(IdentityParser::class);

        return new IdentityParser(new DateParser(), new PhoneParser());
    }

    public function testParse(): void
    {
        $parser = $this->getParser();

        $declarantStatusJson = <<<JSON
  {
    "declarantStatus": {
      "code": 1,
      "label": "pel.complaint.identity.victim"
    }
  }
JSON;

        $identityParsed = json_decode(self::IDENTITY_JSON);

        $declarantStatusParsed = json_decode($declarantStatusJson);

        $identity = $parser->parse($identityParsed->civilState, $identityParsed->contactInformation, $declarantStatusParsed);

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
        $declarantStatusJson = <<<JSON
{
    "declarantStatus": {
        "code": 1,
        "label": "pel.complaint.identity.victim"
    }
}
JSON;

        $identityParsed = json_decode(self::IDENTITY_JSON);
        $declarantStatusParsed = json_decode($declarantStatusJson);

        $identityParsed->civilState->birthLocation->frenchTown = null;
        $identityParsed->civilState->birthLocation->otherTown = 'New York';

        $identity = $parser->parse($identityParsed->civilState, $identityParsed->contactInformation, $declarantStatusParsed);

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertSame('New York', $identity->getBirthCity());
        $this->assertEmpty($identity->getBirthInseeCode());
    }

    public function testParseForeignAddress(): void
    {
        $parser = $this->getParser();
        $declarantStatusJson = <<<JSON
  {
    "declarantStatus": {
      "code": 1,
      "label": "pel.complaint.identity.victim"
    }
  }
JSON;

        $identityParsed = json_decode(self::IDENTITY_JSON);
        $declarantStatusParsed = json_decode($declarantStatusJson);

        $identityParsed->contactInformation->frenchAddress = null;

        $identity = $parser->parse($identityParsed->civilState, $identityParsed->contactInformation, $declarantStatusParsed);

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertSame('1', $identity->getAddressStreetNumber());
        $this->assertSame('Route de Dracy', $identity->getAddressStreetName());
        $this->assertSame('71490', $identity->getAddressPostcode());
        $this->assertSame('Couches', $identity->getAddressCity());
    }

    public function testParseReprensentedPerson(): void
    {
        $parser = $this->getParser();

        $declarantStatusJson = <<<JSON
  {
    "declarantStatus": {
      "code": 1,
      "label": "pel.complaint.identity.victim"
    }
  }
JSON;

        $identityParsed = json_decode(self::IDENTITY_JSON);

        $declarantStatusParsed = json_decode($declarantStatusJson);

        $identity = $parser->parse($identityParsed->representedPersonCivilState, $identityParsed->representedPersonContactInformation);

        $this->assertInstanceOf(Identity::class, $identity);

        $this->assertNull($identity->getDeclarantStatus());
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
