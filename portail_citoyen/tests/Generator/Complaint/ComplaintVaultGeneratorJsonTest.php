<?php

declare(strict_types=1);

namespace App\Tests\Generator\Complaint;

use App\Enum\DeclarantStatus;
use App\Generator\Complaint\ComplaintVaultGeneratorJson;
use App\Tests\Factory\ComplaintFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class ComplaintVaultGeneratorJsonTest extends KernelTestCase
{
    private readonly ComplaintVaultGeneratorJson $generator;
    private readonly ComplaintFactory $complaintFactory;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ComplaintVaultGeneratorJson $generator */
        $generator = $container->get(ComplaintVaultGeneratorJson::class);
        $this->generator = $generator;

        /** @var ComplaintFactory $complaintFactory */
        $complaintFactory = $container->get(ComplaintFactory::class);
        $this->complaintFactory = $complaintFactory;
    }

    /**
     * @throws \JsonException
     */
    public function testGenerateVictim(): void
    {
        $id = new Uuid('698d4e06-c4c4-11ed-af78-973b2c1a1c97');
        $createdAt = new \DateTimeImmutable('2023-03-17T13:05:38+00:00', new \DateTimeZone('UTC'));

        $complaintJson = $this->generator->generate($this->complaintFactory->create(DeclarantStatus::Victim->value, $id, $createdAt));

        $complaintJsonExpected = json_encode([
            'id' => '698d4e06-c4c4-11ed-af78-973b2c1a1c97',
            'createdAt' => [
                'date' => '2023-03-17T13:05:38+00:00',
                'timestamp' => 1679058338,
                'timezone' => '+00:00',
            ],
            'declarantStatus' => [
                'declarantStatus' => [
                    'code' => 1,
                    'label' => 'pel.complaint.identity.victim',
                ],
            ],
            'appointment' => [
                'appointmentContactText' => 'Entre 10h et 14h les lundi',
            ],
            'identity' => [
                'civilState' => [
                    'civility' => [
                        'code' => 1,
                        'label' => 'pel.m',
                    ],
                    'birthName' => 'Dupont',
                    'usageName' => 'Paul',
                    'firstnames' => 'Jean',
                    'birthDate' => [
                        'date' => '1980-01-01T00:00:00+00:00',
                        'timestamp' => 315532800,
                        'timezone' => 'UTC',
                    ],
                    'birthLocation' => [
                        'country' => [
                            'inseeCode' => 99100,
                            'label' => 'France',
                        ],
                        'frenchTown' => [
                            'inseeCode' => '75107',
                            'label' => 'Paris 7e arrondissement',
                        ],
                        'otherTown' => null,
                    ],
                    'nationality' => [
                        'code' => 1,
                        'label' => 'Française',
                    ],
                    'job' => [
                        'code' => '1',
                        'label' => 'Abat-jouriste',
                    ],
                ],
                'contactInformation' => [
                    'phone' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'mobile' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'country' => [
                        'inseeCode' => 99100,
                        'label' => 'France',
                    ],
                    'frenchAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'foreignAddress' => null,
                    'email' => 'jean.dupont@example.com',
                ],
                'representedPersonCivilState' => null,
                'representedPersonContactInformation' => null,
                'corporation' => null,
            ],
            'facts' => [
                'address' => [
                    'addressOrRouteFactsKnown' => false,
                    'addressAdditionalInformation' => 'Additional information',
                    'startAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'endAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                ],
                'offenseDate' => [
                    'exactDateKnown' => false,
                    'choiceHour' => 'maybe',
                    'startDate' => [
                        'date' => '2021-01-01T00:00:00+00:00',
                        'timestamp' => 1609459200,
                        'timezone' => 'UTC',
                    ],
                    'endDate' => [
                        'date' => '2021-01-02T00:00:00+00:00',
                        'timestamp' => 1609545600,
                        'timezone' => 'UTC',
                    ],
                    'hour' => null,
                    'startHour' => [
                        'date' => '2021-01-01T12:00:00+00:00',
                        'timestamp' => 1609502400,
                        'timezone' => 'UTC',
                    ],
                    'endHour' => [
                        'date' => '2021-01-01T13:00:00+00:00',
                        'timestamp' => 1609506000,
                        'timezone' => 'UTC',
                    ],
                ],
                'placeNature' => [
                    'code' => 1,
                    'label' => 'pel.nature.place.home',
                ],
                'victimOfViolence' => true,
                'victimOfViolenceText' => 'Violence text',
                'description' => 'Description',
            ],
            'additionalInformation' => [
                'suspectsChoice' => true,
                'witnesses' => true,
                'fsiVisit' => true,
                'cctvPresent' => [
                    'code' => 1,
                    'label' => 'pel.yes',
                ],
                'suspectsText' => 'Suspects text',
                'witnessesText' => 'Witnesses text',
                'observationMade' => true,
                'cctvAvailable' => true,
            ],
            'objects' => [
                'objects' => [
                    [
                        'status' => [
                            'code' => 1,
                            'label' => 'pel.stolen',
                        ],
                        'category' => [
                            'code' => 1,
                            'label' => 'pel.object.category.documents',
                        ],
                        'label' => 'CI',
                        'brand' => null,
                        'model' => null,
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 100,
                        'documentType' => null,
                        'otherDocumentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 2,
                            'label' => 'pel.degraded',
                        ],
                        'category' => [
                            'code' => 3,
                            'label' => 'pel.object.category.multimedia',
                        ],
                        'label' => 'iPhone',
                        'brand' => 'Apple',
                        'model' => 'iPhone 12',
                        'phoneNumberLine' => [
                            'country' => 'FR',
                            'code' => '33',
                            'number' => '0601020304',
                        ],
                        'operator' => 'Orange',
                        'serialNumber' => '1234567890',
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 2000,
                        'documentType' => null,
                        'otherDocumentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 1,
                            'label' => 'pel.stolen',
                        ],
                        'category' => [
                            'code' => 2,
                            'label' => 'pel.object.category.payment.ways',
                        ],
                        'label' => 'CB',
                        'brand' => null,
                        'model' => null,
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => 'BNP Paribas',
                        'bankAccountNumber' => '1234567890',
                        'creditCardNumber' => '4624 7482 3324 9080',
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 10,
                        'documentType' => null,
                        'otherDocumentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 2,
                            'label' => 'pel.degraded',
                        ],
                        'category' => [
                            'code' => 4,
                            'label' => 'pel.object.category.registered.vehicle',
                        ],
                        'label' => 'Voiture',
                        'brand' => 'Peugeot',
                        'model' => '208',
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => 'AB-123-CD',
                        'registrationNumberCountry' => 'FR',
                        'insuranceCompany' => 'AXA',
                        'insuranceNumber' => '1234567890',
                        'amount' => 10000,
                        'documentType' => null,
                        'otherDocumentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'files' => [],
                    ],
                ],
            ],
            'franceConnected' => false,
            'affectedService' => '66459',
        ], JSON_THROW_ON_ERROR);

        self::assertJson($complaintJson);
        self::assertJsonStringEqualsJsonString($complaintJsonExpected, $complaintJson);
    }

    /**
     * @throws \JsonException
     */
    public function testGeneratePersonLegalRepresentative(): void
    {
        $id = new Uuid('698d4e06-c4c4-11ed-af78-973b2c1a1c97');
        $createdAt = new \DateTimeImmutable('2023-03-17T13:05:38+00:00', new \DateTimeZone('UTC'));

        $complaintJson = $this->generator->generate($this->complaintFactory->create(DeclarantStatus::PersonLegalRepresentative->value, $id, $createdAt, false));
        $complaintJsonExpected = json_encode([
            'id' => '698d4e06-c4c4-11ed-af78-973b2c1a1c97',
            'createdAt' => [
                'date' => '2023-03-17T13:05:38+00:00',
                'timestamp' => 1679058338,
                'timezone' => '+00:00',
            ],
            'declarantStatus' => [
                'declarantStatus' => [
                    'code' => 2,
                    'label' => 'pel.complaint.identity.person.legal.representative',
                ],
            ],
            'appointment' => [
                'appointmentContactText' => 'Entre 10h et 14h les lundi',
            ],
            'identity' => [
                'civilState' => [
                    'civility' => [
                        'code' => 1,
                        'label' => 'pel.m',
                    ],
                    'birthName' => 'Dupont',
                    'usageName' => 'Paul',
                    'firstnames' => 'Jean',
                    'birthDate' => [
                        'date' => '1980-01-01T00:00:00+00:00',
                        'timestamp' => 315532800,
                        'timezone' => 'UTC',
                    ],
                    'birthLocation' => [
                        'country' => [
                            'inseeCode' => 99134,
                            'label' => 'Espagne',
                        ],
                        'frenchTown' => [
                            'inseeCode' => null,
                            'label' => null,
                        ],
                        'otherTown' => 'Madrid',
                    ],
                    'nationality' => [
                        'code' => 1,
                        'label' => 'Française',
                    ],
                    'job' => [
                        'code' => '1',
                        'label' => 'Abat-jouriste',
                    ],
                ],
                'contactInformation' => [
                    'phone' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'mobile' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'country' => [
                        'inseeCode' => 99134,
                        'label' => 'Espagne',
                    ],
                    'frenchAddress' => null,
                    'foreignAddress' => [
                        'addressType' => 'etalab_address',
                        'apartment' => '2',
                        'id' => null,
                        'type' => 'Av.',
                        'score' => null,
                        'houseNumber' => '134',
                        'street' => 'Roque Nublo',
                        'name' => null,
                        'postcode' => '28223',
                        'citycode' => null,
                        'city' => 'Madrid',
                        'district' => null,
                        'context' => 'Pozuelo de Alarcón',
                        'x' => null,
                        'y' => null,
                        'importance' => null,
                        'label' => null,
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'email' => 'jean.dupont@example.com',
                ],
                'representedPersonCivilState' => [
                    'civility' => [
                        'code' => 1,
                        'label' => 'pel.m',
                    ],
                    'birthName' => 'Dupont',
                    'usageName' => 'Paul',
                    'firstnames' => 'Jean',
                    'birthDate' => [
                        'date' => '1980-01-01T00:00:00+00:00',
                        'timestamp' => 315532800,
                        'timezone' => 'UTC',
                    ],
                    'birthLocation' => [
                        'country' => [
                            'inseeCode' => 99134,
                            'label' => 'Espagne',
                        ],
                        'frenchTown' => [
                            'inseeCode' => null,
                            'label' => null,
                        ],
                        'otherTown' => 'Madrid',
                    ],
                    'nationality' => [
                        'code' => 1,
                        'label' => 'Française',
                    ],
                    'job' => [
                        'code' => '1',
                        'label' => 'Abat-jouriste',
                    ],
                ],
                'representedPersonContactInformation' => [
                    'phone' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'mobile' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'country' => [
                        'inseeCode' => 99134,
                        'label' => 'Espagne',
                    ],
                    'frenchAddress' => null,
                    'foreignAddress' => [
                        'addressType' => 'etalab_address',
                        'apartment' => '2',
                        'id' => null,
                        'type' => 'Av.',
                        'score' => null,
                        'houseNumber' => '134',
                        'street' => 'Roque Nublo',
                        'name' => null,
                        'postcode' => '28223',
                        'citycode' => null,
                        'city' => 'Madrid',
                        'district' => null,
                        'context' => 'Pozuelo de Alarcón',
                        'x' => null,
                        'y' => null,
                        'importance' => null,
                        'label' => null,
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'email' => 'jean.dupont@example.com',
                ],
                'corporation' => null,
            ],
            'facts' => [
                'address' => [
                    'addressOrRouteFactsKnown' => false,
                    'addressAdditionalInformation' => 'Additional information',
                    'startAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'endAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                ],
                'offenseDate' => [
                    'exactDateKnown' => false,
                    'choiceHour' => 'maybe',
                    'startDate' => [
                        'date' => '2021-01-01T00:00:00+00:00',
                        'timestamp' => 1609459200,
                        'timezone' => 'UTC',
                    ],
                    'endDate' => [
                        'date' => '2021-01-02T00:00:00+00:00',
                        'timestamp' => 1609545600,
                        'timezone' => 'UTC',
                    ],
                    'hour' => null,
                    'startHour' => [
                        'date' => '2021-01-01T12:00:00+00:00',
                        'timestamp' => 1609502400,
                        'timezone' => 'UTC',
                    ],
                    'endHour' => [
                        'date' => '2021-01-01T13:00:00+00:00',
                        'timestamp' => 1609506000,
                        'timezone' => 'UTC',
                    ],
                ],
                'placeNature' => [
                    'code' => 1,
                    'label' => 'pel.nature.place.home',
                ],
                'victimOfViolence' => true,
                'victimOfViolenceText' => 'Violence text',
                'description' => 'Description',
            ],
            'additionalInformation' => [
                'suspectsChoice' => true,
                'witnesses' => true,
                'fsiVisit' => true,
                'cctvPresent' => [
                    'code' => 1,
                    'label' => 'pel.yes',
                ],
                'suspectsText' => 'Suspects text',
                'witnessesText' => 'Witnesses text',
                'observationMade' => true,
                'cctvAvailable' => true,
            ],
            'objects' => [
                'objects' => [
                    [
                        'status' => [
                            'code' => 1,
                            'label' => 'pel.stolen',
                        ],
                        'category' => [
                            'code' => 1,
                            'label' => 'pel.object.category.documents',
                        ],
                        'label' => 'CI',
                        'brand' => null,
                        'model' => null,
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 100,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 2,
                            'label' => 'pel.degraded',
                        ],
                        'category' => [
                            'code' => 3,
                            'label' => 'pel.object.category.multimedia',
                        ],
                        'label' => 'iPhone',
                        'brand' => 'Apple',
                        'model' => 'iPhone 12',
                        'phoneNumberLine' => [
                            'country' => 'FR',
                            'code' => '33',
                            'number' => '0601020304',
                        ],
                        'operator' => 'Orange',
                        'serialNumber' => '1234567890',
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 2000,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 1,
                            'label' => 'pel.stolen',
                        ],
                        'category' => [
                            'code' => 2,
                            'label' => 'pel.object.category.payment.ways',
                        ],
                        'label' => 'CB',
                        'brand' => null,
                        'model' => null,
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => 'BNP Paribas',
                        'bankAccountNumber' => '1234567890',
                        'creditCardNumber' => '4624 7482 3324 9080',
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 10,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 2,
                            'label' => 'pel.degraded',
                        ],
                        'category' => [
                            'code' => 4,
                            'label' => 'pel.object.category.registered.vehicle',
                        ],
                        'label' => 'Voiture',
                        'brand' => 'Peugeot',
                        'model' => '208',
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => 'AB-123-CD',
                        'registrationNumberCountry' => 'FR',
                        'insuranceCompany' => 'AXA',
                        'insuranceNumber' => '1234567890',
                        'amount' => 10000,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                ],
            ],
            'franceConnected' => false,
            'affectedService' => '66459',
        ], JSON_THROW_ON_ERROR);

        self::assertJson($complaintJson);
        self::assertJsonStringEqualsJsonString($complaintJsonExpected, $complaintJson);
    }

    /**
     * @throws \JsonException
     */
    public function testGenerateCorporationLegalRepresentative(): void
    {
        $id = new Uuid('698d4e06-c4c4-11ed-af78-973b2c1a1c97');
        $createdAt = new \DateTimeImmutable('2023-03-17T13:05:38+00:00', new \DateTimeZone('UTC'));

        $complaintJson = $this->generator->generate($this->complaintFactory->create(DeclarantStatus::CorporationLegalRepresentative->value, $id, $createdAt));
        $complaintJsonExpected = json_encode([
            'id' => '698d4e06-c4c4-11ed-af78-973b2c1a1c97',
            'createdAt' => [
                'date' => '2023-03-17T13:05:38+00:00',
                'timestamp' => 1679058338,
                'timezone' => '+00:00',
            ],
            'declarantStatus' => [
                'declarantStatus' => [
                    'code' => 3,
                    'label' => 'pel.complaint.identity.corporation.legal.representative',
                ],
            ],
            'appointment' => [
                'appointmentContactText' => 'Entre 10h et 14h les lundi',
            ],
            'identity' => [
                'civilState' => [
                    'civility' => [
                        'code' => 1,
                        'label' => 'pel.m',
                    ],
                    'birthName' => 'Dupont',
                    'usageName' => 'Paul',
                    'firstnames' => 'Jean',
                    'birthDate' => [
                        'date' => '1980-01-01T00:00:00+00:00',
                        'timestamp' => 315532800,
                        'timezone' => 'UTC',
                    ],
                    'birthLocation' => [
                        'country' => [
                            'inseeCode' => 99100,
                            'label' => 'France',
                        ],
                        'frenchTown' => [
                            'inseeCode' => '75107',
                            'label' => 'Paris 7e arrondissement',
                        ],
                        'otherTown' => null,
                    ],
                    'nationality' => [
                        'code' => 1,
                        'label' => 'Française',
                    ],
                    'job' => [
                        'code' => '1',
                        'label' => 'Abat-jouriste',
                    ],
                ],
                'contactInformation' => [
                    'phone' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'mobile' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'country' => [
                        'inseeCode' => 99100,
                        'label' => 'France',
                    ],
                    'frenchAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'foreignAddress' => null,
                    'email' => 'jean.dupont@example.com',
                ],
                'representedPersonCivilState' => null,
                'representedPersonContactInformation' => null,
                'corporation' => [
                    'siren' => '123456789',
                    'name' => 'Entreprise',
                    'function' => 'CEO',
                    'nationality' => [
                        'code' => '1',
                        'label' => 'pel.nationality.france',
                    ],
                    'phone' => [
                        'country' => 'FR',
                        'code' => '33',
                        'number' => '0601020304',
                    ],
                    'country' => [
                        'inseeCode' => 99100,
                        'label' => 'France',
                    ],
                    'frenchAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'foreignAddress' => null,
                    'email' => 'entreprise@gmail.com',
                ],
            ],
            'facts' => [
                'address' => [
                    'addressOrRouteFactsKnown' => false,
                    'addressAdditionalInformation' => 'Additional information',
                    'startAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    'endAddress' => [
                        'addressType' => 'etalab_address',
                        'id' => '80021_6590_00008',
                        'type' => 'housenumber',
                        'score' => 0.49219200956938,
                        'houseNumber' => '8',
                        'street' => 'Boulevard du Port',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'city' => 'Amiens',
                        'district' => null,
                        'context' => '80, Somme, Hauts-de-France',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'importance' => 0.67727,
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                ],
                'offenseDate' => [
                    'exactDateKnown' => false,
                    'choiceHour' => 'maybe',
                    'startDate' => [
                        'date' => '2021-01-01T00:00:00+00:00',
                        'timestamp' => 1609459200,
                        'timezone' => 'UTC',
                    ],
                    'endDate' => [
                        'date' => '2021-01-02T00:00:00+00:00',
                        'timestamp' => 1609545600,
                        'timezone' => 'UTC',
                    ],
                    'hour' => null,
                    'startHour' => [
                        'date' => '2021-01-01T12:00:00+00:00',
                        'timestamp' => 1609502400,
                        'timezone' => 'UTC',
                    ],
                    'endHour' => [
                        'date' => '2021-01-01T13:00:00+00:00',
                        'timestamp' => 1609506000,
                        'timezone' => 'UTC',
                    ],
                ],
                'placeNature' => [
                    'code' => 1,
                    'label' => 'pel.nature.place.home',
                ],
                'victimOfViolence' => true,
                'victimOfViolenceText' => 'Violence text',
                'description' => 'Description',
            ],
            'additionalInformation' => [
                'suspectsChoice' => true,
                'witnesses' => true,
                'fsiVisit' => true,
                'cctvPresent' => [
                    'code' => 1,
                    'label' => 'pel.yes',
                ],
                'suspectsText' => 'Suspects text',
                'witnessesText' => 'Witnesses text',
                'observationMade' => true,
                'cctvAvailable' => true,
            ],
            'objects' => [
                'objects' => [
                    [
                        'status' => [
                            'code' => 1,
                            'label' => 'pel.stolen',
                        ],
                        'category' => [
                            'code' => 1,
                            'label' => 'pel.object.category.documents',
                        ],
                        'label' => 'CI',
                        'brand' => null,
                        'model' => null,
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 100,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 2,
                            'label' => 'pel.degraded',
                        ],
                        'category' => [
                            'code' => 3,
                            'label' => 'pel.object.category.multimedia',
                        ],
                        'label' => 'iPhone',
                        'brand' => 'Apple',
                        'model' => 'iPhone 12',
                        'phoneNumberLine' => [
                            'country' => 'FR',
                            'code' => '33',
                            'number' => '0601020304',
                        ],
                        'operator' => 'Orange',
                        'serialNumber' => '1234567890',
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 2000,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 1,
                            'label' => 'pel.stolen',
                        ],
                        'category' => [
                            'code' => 2,
                            'label' => 'pel.object.category.payment.ways',
                        ],
                        'label' => 'CB',
                        'brand' => null,
                        'model' => null,
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => 'BNP Paribas',
                        'bankAccountNumber' => '1234567890',
                        'creditCardNumber' => '4624 7482 3324 9080',
                        'registrationNumber' => null,
                        'registrationNumberCountry' => null,
                        'insuranceCompany' => null,
                        'insuranceNumber' => null,
                        'amount' => 10,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                    [
                        'status' => [
                            'code' => 2,
                            'label' => 'pel.degraded',
                        ],
                        'category' => [
                            'code' => 4,
                            'label' => 'pel.object.category.registered.vehicle',
                        ],
                        'label' => 'Voiture',
                        'brand' => 'Peugeot',
                        'model' => '208',
                        'phoneNumberLine' => null,
                        'operator' => null,
                        'serialNumber' => null,
                        'description' => null,
                        'quantity' => null,
                        'bank' => null,
                        'bankAccountNumber' => null,
                        'creditCardNumber' => null,
                        'registrationNumber' => 'AB-123-CD',
                        'registrationNumberCountry' => 'FR',
                        'insuranceCompany' => 'AXA',
                        'insuranceNumber' => '1234567890',
                        'amount' => 10000,
                        'documentType' => null,
                        'documentOwned' => null,
                        'documentOwner' => null,
                        'otherDocumentType' => null,
                        'files' => [],
                    ],
                ],
            ],
            'franceConnected' => false,
            'affectedService' => '66459',
        ], JSON_THROW_ON_ERROR);

        self::assertJson($complaintJson);
        self::assertJsonStringEqualsJsonString($complaintJsonExpected, $complaintJson);
    }
}
