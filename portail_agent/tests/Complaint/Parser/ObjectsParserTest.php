<?php

declare(strict_types=1);

namespace App\Tests\Complaint\Parser;

use App\Complaint\Parser\ObjectsParser;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ObjectsParserTest extends KernelTestCase
{
    private const TEST_FILE_DIR = 'var/complaints/123456789/';

    private const COMPLAINT_JSON = <<<JSON
{
    "identity":
	{
	    "declarantStatus":
		{
			"code": 1,
			"label": "pel.complaint.identity.victim"
		},
	    "consentContactElectronics": false,
		"civilState":
		{
			"civility":
			{
				"code": 1,
				"label": "pel.m"
			},
			"birthName": "DUPONT",
			"firstnames": "Charles"
        }
    }
}
JSON;

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
            "registeredVehicleNature": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 1500.0,
            "documentType": "1",
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
            ],
            "stillOnWhenMobileStolen": null,
            "keyboardLockedWhenMobileStolen": null,
            "pinEnabledWhenMobileStolen": null,
            "mobileInsured": null,
            "allowOperatorCommunication": null,
            "degradationDescription": null,
            "ownerLastname": null,
            "ownerFirstname": null
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
            "registeredVehicleNature": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": null,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": [],
            "stillOnWhenMobileStolen": null,
            "keyboardLockedWhenMobileStolen": null,
            "pinEnabledWhenMobileStolen": null,
            "mobileInsured": null,
            "allowOperatorCommunication": null,
            "degradationDescription": null
        },
        {
            "status": {
                "code": 1,
                "label": "pel.stolen"
            },
            "category": {
                "code": 7,
                "label": "pel.object.category.multimedia"
            },
            "label": "PC",
            "brand": "Dell",
            "model": "Inspiron 7",
            "phoneNumberLine": null,
            "operator": null,
            "serialNumber": null,
            "description": "Description PC",
            "quantity": null,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registeredVehicleNature": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 4000.0,
            "documentType": null,
            "otherDocumentType": null,
            "documentOwned": null,
            "documentAdditionalInformation": null,
            "files": [],
            "stillOnWhenMobileStolen": true,
            "keyboardLockedWhenMobileStolen": true,
            "pinEnabledWhenMobileStolen": true,
            "mobileInsured": true,
            "allowOperatorCommunication": true,
            "degradationDescription": null,
            "ownerLastname": "DURAND",
            "ownerFirstname": "Charles"
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
            "registeredVehicleNature": "CAMION",
            "registrationNumber": "123BG30",
            "registrationNumberCountry": "FR",
            "insuranceCompany": "AXA",
            "insuranceNumber": "123456789",
            "amount": 15000.0,
            "documentType": null,
            "otherDocumentType": null,
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
            "ownerFirstname": null
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
            "registeredVehicleNature": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 560.0,
            "documentType": null,
            "otherDocumentType": null,
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
            "ownerFirstname": null
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
            "registeredVehicleNature": null,
            "registrationNumber": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 650.0,
            "documentType": null,
            "otherDocumentType": null,
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
            "ownerFirstname": null
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
            "description": "Description téléphone",
            "quantity": null,
            "bank": null,
            "bankAccountNumber": null,
            "creditCardNumber": null,
            "registrationNumber": null,
            "registeredVehicleNature": null,
            "registrationNumberCountry": null,
            "insuranceCompany": null,
            "insuranceNumber": null,
            "amount": 1200.0,
            "documentType": null,
            "otherDocumentType": null,
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
            "ownerFirstname": null
        }
    ]
}
JSON;

    public function setUp(): void
    {
        parent::setUp();

        // Testing file
        if (!file_exists(self::TEST_FILE_DIR)) {
            mkdir(self::TEST_FILE_DIR, 0777, true);
        }

        fopen(self::TEST_FILE_DIR.'iphone.png', 'wb');
    }

    public function getParser(): ObjectsParser
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ObjectsParser $parser */
        $parser = $container->get(ObjectsParser::class);
        $parser->setComplaintFrontId('123456789');

        return $parser;
    }

    public function testParseAll(): void
    {
        /** @var object $complaintJson */
        $complaintJson = json_decode(self::COMPLAINT_JSON);
        $objectsInput = json_decode(self::OBJECTS_JSON);
        $objectsParser = $this->getParser();

        $objects = $objectsParser->parseAll($objectsInput->objects, $complaintJson);

        $this->assertIsArray($objects);
    }

    public function testParseAdministrativeDocument(): void
    {
        /** @var object $complaintJson */
        $complaintJson = json_decode(self::COMPLAINT_JSON);
        $objectsInput = json_decode(self::OBJECTS_JSON);
        $objectsParser = $this->getParser();

        // $objects[0] is an AdministrativeDocument
        $administrativeDocument = $objectsParser->parse($objectsInput->objects[0], $complaintJson);

        $this->assertInstanceOf(AdministrativeDocument::class, $administrativeDocument);

        // @TODO Voir comment récupérer ça
        // $this->assertSame(1, $administrativeDocument->getType());
        $this->assertFalse($administrativeDocument->isOwned());
        $this->assertSame('DUPONT', $administrativeDocument->getOwnerLastname());
        $this->assertSame('Jean', $administrativeDocument->getOwnerFirstname());
        $this->assertSame('DUPONT', $administrativeDocument->getOwnerLastname());
        $this->assertSame('Test', $administrativeDocument->getOwnerCompany());
        $this->assertNull($administrativeDocument->getOwnerEmail());
        $this->assertSame('104 Rue Mélanie 67000 Strasbourg', $administrativeDocument->getOwnerAddress());
        $this->assertSame('104', $administrativeDocument->getOwnerAddressStreetNumber());
        $this->assertSame('Rue Mélanie', $administrativeDocument->getOwnerAddressStreetName());
        $this->assertSame('67000', $administrativeDocument->getOwnerAddressPostcode());
        $this->assertSame('67482', $administrativeDocument->getOwnerAddressInseeCode());
        $this->assertSame('Strasbourg', $administrativeDocument->getOwnerAddressCity());
        $this->assertSame('67', $administrativeDocument->getOwnerAddressDepartmentNumber());
        $this->assertSame('Bas-Rhin', $administrativeDocument->getOwnerAddressDepartment());
        $this->assertNull($administrativeDocument->getNumber());
        $this->assertNull($administrativeDocument->getIssuedBy());
        $this->assertNull($administrativeDocument->getIssuedOn());
        $this->assertNull($administrativeDocument->getValidityEndDate());
        $this->assertSame(1500.0, $administrativeDocument->getAmount());
        $this->assertNotEmpty($administrativeDocument->getFiles());
    }

    public function testParsePaymentMethod(): void
    {
        /** @var object $complaintJson */
        $complaintJson = json_decode(self::COMPLAINT_JSON);
        $objectsInput = json_decode(self::OBJECTS_JSON);
        $objectsParser = $this->getParser();

        // $objects[1] is a PaymentMethod
        $paymentMethod = $objectsParser->parse($objectsInput->objects[1], $complaintJson);

        $this->assertInstanceOf(PaymentMethod::class, $paymentMethod);
        $this->assertSame('Carte bleu', $paymentMethod->getType());
        $this->assertEmpty($paymentMethod->getDescription());
        $this->assertSame('BNP', $paymentMethod->getBank());
    }

    public function testParseMultimediaObject(): void
    {
        /** @var object $complaintJson */
        $complaintJson = json_decode(self::COMPLAINT_JSON);
        $objectsInput = json_decode(self::OBJECTS_JSON);
        $objectsParser = $this->getParser();

        // $objects[2] is a MultimediaObject
        $object = $objectsParser->parse($objectsInput->objects[2], $complaintJson);

        $this->assertInstanceOf(MultimediaObject::class, $object);
        $this->assertSame('PC', $object->getLabel());
        $this->assertSame('Dell', $object->getBrand());
        $this->assertSame('Inspiron 7', $object->getModel());
        $this->assertNull($object->getOperator());
        $this->assertNull($object->getSerialNumber());
        $this->assertNull($object->getPhoneNumber());
        $this->assertSame('Description PC', $object->getDescription());
        $this->assertSame('DURAND', $object->getOwnerLastname());
        $this->assertSame('Charles', $object->getOwnerFirstname());
        $this->assertSame(4000.0, $object->getAmount());

        // $objects[6] is a MultimediaObject
        $object = $objectsParser->parse($objectsInput->objects[6], $complaintJson);

        $this->assertInstanceOf(MultimediaObject::class, $object);
        $this->assertSame('iPhone 11', $object->getLabel());
        $this->assertSame('Apple', $object->getBrand());
        $this->assertSame('Iphone 11', $object->getModel());
        $this->assertSame('SFR', $object->getOperator());
        $this->assertSame('111222333343', $object->getSerialNumber());
        $this->assertSame('+33 649956685', $object->getPhoneNumber());
        $this->assertSame('Description téléphone', $object->getDescription());
        $this->assertSame(1200.0, $object->getAmount());
        $this->assertTrue($object->isKeyboardLockedWhenMobileStolen());
        $this->assertTrue($object->isMobileInsured());
        $this->assertTrue($object->isStillOnWhenMobileStolen());
        $this->assertTrue($object->isPinEnabledWhenMobileStolen());
        $this->assertNull($object->getOwnerLastname());
        $this->assertNull($object->getOwnerFirstname());
    }

    public function testParseRegisteredVehicle(): void
    {
        /** @var object $complaintJson */
        $complaintJson = json_decode(self::COMPLAINT_JSON);
        $objectsInput = json_decode(self::OBJECTS_JSON);
        $objectsParser = $this->getParser();

        // $objects[3] is a Registered Vehicle
        $object = $objectsParser->parse($objectsInput->objects[3], $complaintJson);

        $this->assertInstanceOf(Vehicle::class, $object);
        $this->assertSame('Voiture', $object->getLabel());
        $this->assertSame('BMW', $object->getBrand());
        $this->assertSame('X3', $object->getModel());
        $this->assertSame('123BG30', $object->getRegistrationNumber());
        $this->assertSame('FR', $object->getRegistrationCountry());
        $this->assertSame('AXA', $object->getInsuranceCompany());
        $this->assertSame('123456789', $object->getInsuranceNumber());
        $this->assertSame('CAMION', $object->getNature());
        $this->assertSame(15000.0, $object->getAmount());
        $this->assertSame('Rétroviseur cassé', $object->getDegradationDescription());
    }

    public function testParseUnregisteredVehicle(): void
    {
        /** @var object $complaintJson */
        $complaintJson = json_decode(self::COMPLAINT_JSON);
        $objectsInput = json_decode(self::OBJECTS_JSON);
        $objectsParser = $this->getParser();

        // $objects[4] is a Unregistered Vehicle
        $object = $objectsParser->parse($objectsInput->objects[4], $complaintJson);

        $this->assertInstanceOf(Vehicle::class, $object);
        $this->assertSame('Vélo', $object->getLabel());
        $this->assertEmpty($object->getBrand());
        $this->assertNull($object->getModel());
        $this->assertNull($object->getRegistrationNumber());
        $this->assertNull($object->getRegistrationCountry());
        $this->assertNull($object->getInsuranceCompany());
        $this->assertNull($object->getInsuranceNumber());
        $this->assertNull($object->getNature());
        $this->assertSame(560.0, $object->getAmount());
    }

    public function testParseSimpleObject(): void
    {
        /** @var object $complaintJson */
        $complaintJson = json_decode(self::COMPLAINT_JSON);
        $objectsInput = json_decode(self::OBJECTS_JSON);
        $objectsParser = $this->getParser();

        // $objects[5] is a SimpleObject
        $object = $objectsParser->parse($objectsInput->objects[5], $complaintJson);

        $this->assertInstanceOf(SimpleObject::class, $object);
        $this->assertSame('oeil d\'ophidia', $object->getNature());
        $this->assertSame('Carte de collection', $object->getDescription());
        $this->assertSame(650.0, $object->getAmount());
    }
}
