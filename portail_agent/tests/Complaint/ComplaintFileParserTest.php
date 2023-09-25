<?php

declare(strict_types=1);

namespace App\Tests\Complaint;

use App\Complaint\ComplaintFileParser;
use App\Entity\AdditionalInformation;
use App\Entity\Corporation;
use App\Entity\Facts;
use App\Entity\FactsObjects\AbstractObject;
use App\Entity\Identity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ComplaintFileParserTest extends KernelTestCase
{
    public function getParser(): ComplaintFileParser
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ComplaintFileParser $parser */
        $parser = $container->get(ComplaintFileParser::class);

        return $parser;
    }

    public function testParseEverythingIsGood(): void
    {
        $parser = $this->getParser();

        $complaintFileContent = <<<JSON
{
	"id": "698d4e06-c4c4-11ed-af78-973b2c1a1c97",
	"createdAt":
	{
		"date": "2023-03-17T13:05:38+00:00",
		"timestamp": 1679058338,
		"timezone": "+00:00"
	},
	"appointmentRequired": true,
	"identity":
	{
	    "declarantStatus":
		{
			"code": 1,
			"label": "Vous êtes victime ou vous représentez votre enfant mineur"
		},
	    "consentContactEmail": false,
	    "consentContactSMS": true,
	    "consentContactPortal": false,
		"civilState":
		{
			"civility":
			{
				"code": 1,
				"label": "M"
			},
			"birthName": "Dupont",
			"usageName": "Paul",
			"firstnames": "Jean",
			"familySituation": {
                "code": 1,
                "label": "Célibataire"
            },
			"birthDate":
			{
				"date": "1980-01-01T00:00:00+00:00",
				"timestamp": 315532800,
				"timezone": "UTC"
			},
			"birthLocation":
			{
				"country":
				{
					"inseeCode": 99160,
					"label": "France"
				},
				"frenchTown":
				{
					"inseeCode": "75107",
					"label": "Paris 7e arrondissement",
					"postalCode": "75007",
					"departmentCode": "75",
					"departmentLabel": "Paris"
				},
				"otherTown": null
			},
			"nationality":
			{
				"code": 23,
				"label": "FRANCAISE"
			},
			"job":
			{
				"code": "abat-jouriste",
				"label": "Abat-jouriste"
			}
		},
		"contactInformation":
		{
			"phone":
			{
				"country": "FR",
				"code": "33",
				"number": "0601020304"
			},
			"mobile":
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
			"email": "jean.dupont@example.com"
		},
		"representedPersonCivilState": null,
		"representedPersonContactInformation": null,
		"corporation": {
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
	},
	"facts":
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
		"placeNature": "RESTAURANT",
		"victimOfViolence": true,
		"victimOfViolenceText": "Violence text",
		"description": "Description",
		"callingPhone": null,
		"website": null
	},
	"additionalInformation":
	{
		"suspectsChoice": true,
		"witnessesPresent": true,
		"fsiVisit": true,
		"cctvPresent":
		{
			"code": 1,
			"label": "Oui"
		},
		"suspectsText": "Suspects text",
		"witnesses": [
	    {
            "description": "Jean DUPONT",
            "email": "jean.dupont@example.com",
            "phone": {"country":"FR","code":"33","number":"0601020304"}
	    }],
		"observationMade": true,
		"cctvAvailable": true
	},
	"objects":
	{
		"objects": [
		{
			"status":
			{
				"code": 1,
				"label": "Volé"
			},
			"category":
			{
				"code": 1,
				"label": "Document officiel"
			},
			"label": "CI",
			"brand": null,
			"model": null,
			"phoneNumberLine": null,
			"operator": null,
			"serialNumber": null,
			"description": "Description",
			"quantity": null,
			"bank": null,
			"bankAccountNumber": null,
			"creditCardNumber": null,
			"registeredVehicleNature": null,
			"registrationNumber": null,
			"registrationNumberCountry": null,
			"insuranceCompany": null,
			"insuranceNumber": null,
			"amount": 100,
			"documentType": null,
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
                  }
            },
            "documentNumber": "123",
            "documentIssuedBy": "Préfecture de Paris",
            "documentIssuedOn": {
                "date": "2012-08-01T00:00:00+00:00",
                "timestamp": 1243779200,
                "timezone": "+00:00"
            },
            "documentValidityEndDate": {
                "date": "2030-08-01T00:00:00+00:00",
                "timestamp": 1911772800,
                "timezone": "+00:00"
            },
			"files": [],
            "stillOnWhenMobileStolen": null,
            "keyboardLockedWhenMobileStolen": null,
            "pinEnabledWhenMobileStolen": null,
            "mobileInsured": null,
            "allowOperatorCommunication": null,
            "degradationDescription": null,
            "ownerLastname": null,
            "ownerFirstname": null,
            "multimediaNature": null,
            "documentIssuingCountry": {
                "inseeCode": 99160,
                "label": "France"
            },
            "paymentCategory": null,
            "checkNumber": null,
            "checkFirstNumber": null,
            "checkLastNumber": null
		},
		{
			"status":
			{
				"code": 2,
				"label": "Dégradé"
			},
			"category":
			{
				"code": 3,
				"label": "Téléphone portable"
			},
			"label": "iPhone",
			"brand": "Apple",
			"model": "iPhone 12",
			"phoneNumberLine":
			{
				"country": "FR",
				"code": "33",
				"number": "0601020304"
			},
			"operator": "Orange",
			"serialNumber": "1234567890",
			"description": null,
			"quantity": null,
			"bank": null,
			"bankAccountNumber": null,
			"creditCardNumber": null,
			"registeredVehicleNature": null,
			"registrationNumber": null,
			"registrationNumberCountry": null,
			"insuranceCompany": null,
			"insuranceNumber": null,
			"amount": 2000,
			"documentType": null,
			"otherDocumentType": null,
			"documentNumber": null,
            "documentIssuedBy": null,
            "documentIssuedOn": null,
            "documentValidityEndDate": null,
			"documentOwned": null,
			"documentAdditionalInformation": null,
			"files": [],
            "stillOnWhenMobileStolen": true,
            "keyboardLockedWhenMobileStolen": true,
            "pinEnabledWhenMobileStolen": true,
            "mobileInsured": true,
            "allowOperatorCommunication": true,
            "degradationDescription": null,
            "ownerLastname": null,
            "ownerFirstname": null,
            "multimediaNature": null,
            "documentIssuingCountry": null,
            "paymentCategory": null,
            "checkNumber": null,
            "checkFirstNumber": null,
            "checkLastNumber": null
		},
		{
			"status":
			{
				"code": 1,
				"label": "Volé"
			},
			"category":
			{
				"code": 2,
				"label": "Moyens de paiement"
			},
			"label": "CB",
			"brand": null,
			"model": null,
			"phoneNumberLine": null,
			"operator": null,
			"serialNumber": null,
			"description": null,
			"quantity": null,
			"bank": "BNP Paribas",
			"bankAccountNumber": "1234567890",
			"creditCardNumber": "4624 7482 3324 9080",
			"registeredVehicleNature": null,
			"registrationNumber": null,
			"registrationNumberCountry": null,
			"insuranceCompany": null,
			"insuranceNumber": null,
			"amount": 10,
			"documentType": null,
			"otherDocumentType": null,
			"documentNumber": null,
            "documentIssuedBy": null,
            "documentIssuedOn": null,
            "documentValidityEndDate": null,
			"documentOwned": null,
			"documentAdditionalInformation": null,
			"files": [],
            "stillOnWhenMobileStolen": null,
            "keyboardLockedWhenMobileStolen": null,
            "pinEnabledWhenMobileStolen": null,
            "mobileInsured": null,
            "allowOperatorCommunication": null,
            "degradationDescription": null,
            "ownerLastname": null,
            "ownerFirstname": null,
            "multimediaNature": null,
            "documentIssuingCountry": null,
            "paymentCategory": "CARTE BANCAIRE",
            "checkNumber": null,
            "checkFirstNumber": null,
            "checkLastNumber": null
		},
		{
			"status":
			{
				"code": 2,
				"label": "Dégradé"
			},
			"category":
			{
				"code": 4,
				"label": "Véhicules immatriculés"
			},
			"label": "Voiture",
			"brand": "Peugeot",
			"model": "208",
			"phoneNumberLine": null,
			"operator": null,
			"serialNumber": null,
			"description": null,
			"quantity": null,
			"bank": null,
			"bankAccountNumber": null,
			"creditCardNumber": null,
			"registeredVehicleNature": "CAMION",
			"registrationNumber": "AB-123-CD",
			"registrationNumberCountry": "FR",
			"insuranceCompany": "AXA",
			"insuranceNumber": "1234567890",
			"amount": 10000,
			"documentType": null,
			"otherDocumentType": null,
			"documentNumber": null,
            "documentIssuedBy": null,
            "documentIssuedOn": null,
            "documentValidityEndDate": null,
			"documentOwned": null,
			"documentAdditionalInformation": null,
			"files": [],
            "stillOnWhenMobileStolen": null,
            "keyboardLockedWhenMobileStolen": null,
            "pinEnabledWhenMobileStolen": null,
            "mobileInsured": null,
            "allowOperatorCommunication": null,
            "degradationDescription": "Rétroviseur cassé",
            "ownerLastname": null,
            "ownerFirstname": null,
            "multimediaNature": null,
            "documentIssuingCountry": null,
            "paymentCategory": null,
            "checkNumber": null,
            "checkFirstNumber": null,
            "checkLastNumber": null
		}]
	},
	"appointment": {
        "appointmentContactText": "Je suis disponible le lundi et le mercredi entre 9h et 11h et entre 15h et 16h.",
        "appointmentAsked": null
    },
	"franceConnected": false,
	"affectedService": "66459"
}
JSON;

        $complaint = $parser->parse($complaintFileContent);

        $this->assertEquals('698d4e06-c4c4-11ed-af78-973b2c1a1c97', $complaint->getFrontId());
        $this->assertFalse($complaint->isFranceConnected());
        $this->assertEquals('66459', $complaint->getUnitAssigned());
        $this->assertEquals('2023-03-17T13:05:38+00:00', $complaint->getCreatedAt()?->format(DATE_ATOM));
        $this->assertEquals('Je suis disponible le lundi et le mercredi entre 9h et 11h et entre 15h et 16h.', $complaint->getAppointmentContactInformation());
        $this->assertInstanceOf(Identity::class, $complaint->getIdentity());
        $this->assertInstanceOf(Corporation::class, $complaint->getCorporationRepresented());
        $this->assertInstanceOf(Facts::class, $complaint->getFacts());
        $this->assertInstanceOf(AdditionalInformation::class, $complaint->getAdditionalInformation());
        $this->assertTrue($complaint->isAppointmentRequired());
        $this->assertNull($complaint->isAppointmentAsked());
        $this->assertFalse($complaint->isConsentContactEmail());
        $this->assertTrue($complaint->isConsentContactSMS());
        $this->assertFalse($complaint->isConsentContactPortal());
        foreach ($complaint->getObjects() as $object) {
            $this->assertInstanceOf(AbstractObject::class, $object);
        }
    }
}
