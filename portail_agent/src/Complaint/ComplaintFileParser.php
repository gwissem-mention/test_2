<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Complaint\Exceptions\NoAffectedServiceException;
use App\Complaint\Parser\AdditionalInformationParser;
use App\Complaint\Parser\AddressParser;
use App\Complaint\Parser\CorporationParser;
use App\Complaint\Parser\DateParser;
use App\Complaint\Parser\FactsParser;
use App\Complaint\Parser\FileParser;
use App\Complaint\Parser\IdentityParser;
use App\Complaint\Parser\ObjectsParser;
use App\Complaint\Parser\PhoneParser;
use App\Entity\Complaint;
use App\Referential\Repository\UnitRepository;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type JsonPhone from PhoneParser
 * @phpstan-import-type JsonDate from DateParser
 * @phpstan-import-type JsonFile from FileParser
 * @phpstan-import-type JsonAdditionalInformation from AdditionalInformationParser
 * @phpstan-import-type JsonFacts from FactsParser
 * @phpstan-import-type JsonCorporation from CorporationParser
 * @phpstan-import-type JsonCivilState from IdentityParser
 * @phpstan-import-type JsonDeclarantStatus from IdentityParser
 * @phpstan-import-type JsonContactInformation from IdentityParser
 * @phpstan-import-type JsonFrenchAddress from AddressParser
 * @phpstan-import-type JsonForeignAddress from AddressParser
 *
 * @phpstan-type JsonObject object{
 *       category: object{code: int, label: string},
 *       amount: float|null,
 *       status: object{code: int, label: string},
 *       documentType: string|null,
 *       documentOwned: bool,
 *       documentAdditionalInformation: object{
 *            documentOwnerLastName: string|null,
 *            documentOwnerFirstName: string|null,
 *            documentOwnerCompany: string|null,
 *            documentOwnerEmail: string|null,
 *            documentOwnerPhone: JsonPhone|null,
 *            documentOwnerAddress: JsonFrenchAddress|null,
 *       }|null,
 *        documentNumber: string|null,
 *        documentIssuedBy: string|null,
 *        documentIssuedOn: JsonDate|null,
 *        documentValidityEndDate: JsonDate|null,
 *        description: string|null,
 *        documentIssuingCountry: object{label: string}|null,
 *       brand: string,
 *       model: string|null,
 *       serialNumber: string|null,
 *       description: string|null,
 *       multimediaNature: string|null,
 *       operator: string|null,
 *       stillOnWhenMobileStolen: bool|null,
 *       pinEnabledWhenMobileStolen: bool|null,
 *       mobileInsured: bool|null,
 *       keyboardLockedWhenMobileStolen: bool|null,
 *       ownerLastname: string|null,
 *       ownerFirstname: string|null,
 *       phoneNumberLine: JsonPhone|null,
 *       registrationNumber: string|null,
 *       registrationNumberCountry: string|null,
 *       insuranceCompany: string|null,
 *       insuranceNumber: string|null,
 *       registeredVehicleNature: string|null,
 *       degradationDescription: string|null,
 *       label: string,
 *       paymentCategory: string,
 *       bank: string|null,
 *       bankAccountNumber: string|null,
 *       checkNumber: string|null,
 *       checkFirstNumber: string|null,
 *       checkLastNumber: string|null,
 *       files: array<JsonFile>,
 *   }
 * @phpstan-type JsonComplaint object{
 *      identity: object{
 *           civilState: JsonCivilState,
 *           contactInformation: JsonContactInformation,
 *           declarantStatus: JsonDeclarantStatus|null,
 *           corporation: JsonCorporation|null,
 *           representedPersonCivilState: object|null,
 *           representedPersonContactInformation: object|null,
 *           consentContactElectronics: bool
 *      },
 *      facts: JsonFacts,
 *      objects: object{objects: array<JsonObject>},
 *      additionalInformation: JsonAdditionalInformation,
 *      id: string,
 *      createdAt: JsonDate,
 *      franceConnected: bool,
 *      appointmentRequired: bool,
 *      appointment: object{appointmentAsked: bool, appointmentContactText: string}|null,
 *      affectedService: string|null,
 *  }
 */
class ComplaintFileParser
{
    public function __construct(
        protected readonly UnitRepository $unitRepository,
        protected readonly LoggerInterface $logger,
        protected readonly IdentityParser $identityParser,
        protected readonly FactsParser $factsParser,
        protected readonly ObjectsParser $objectsParser,
        protected readonly AdditionalInformationParser $additionalInformationParser,
        protected readonly CorporationParser $corporationParser
    ) {
    }

    public function parse(string $complaintFileContent): Complaint
    {
        /** @var JsonComplaint $complaintJson */
        $complaintJson = json_decode($complaintFileContent, false, 512, JSON_THROW_ON_ERROR);

        $complaint = $this->parseComplaint($complaintJson);

        $complaint
            ->setIdentity($this->identityParser->parse($complaintJson->identity->civilState, $complaintJson->identity->contactInformation, $complaintJson->identity->declarantStatus))
            ->setFacts($this->factsParser->parse($complaintJson->facts, $complaintJson->objects->objects))
            ->setAdditionalInformation($this->additionalInformationParser->parse($complaintJson->additionalInformation));

        if ($complaintJson->identity->corporation) {
            $complaint->setCorporationRepresented($this->corporationParser->parse($complaintJson->identity->corporation));
        }

        /* Person Legal Representative must be hidden for the experimentation */
        // if ($complaintJson->identity->representedPersonCivilState) {
        //     $complaint->setPersonLegalRepresented($this->identityParser->parse($complaintJson->identity->representedPersonCivilState, $complaintJson->identity->representedPersonContactInformation));
        // }

        $this->objectsParser->setComplaintFrontId($complaint->getFrontId());
        /** @var array<array<JsonObject>> $objectsParents */
        $objectsParents = $complaintJson->objects;
        foreach ($objectsParents as $objects) {
            foreach ($objects as $object) {
                $complaint->addObject($this->objectsParser->parse($object, $complaintJson));
            }
        }

        return $complaint;
    }

    /**
     * @param JsonComplaint $importedComplaint
     *
     * @throws NoAffectedServiceException
     */
    private function parseComplaint(object $importedComplaint): Complaint
    {
        $complaint = new Complaint();

        $dateTimeZone = new \DateTimeZone($importedComplaint->createdAt->timezone);

        $complaint
            ->setTest(false) // TODO : get a real value from the front
            ->setOptinNotification(false) // TODO : get a real value from the front
            ->setFrontId($importedComplaint->id)
            ->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING)
            ->setCreatedAt(new \DateTimeImmutable($importedComplaint->createdAt->date, $dateTimeZone))
            ->setFranceConnected($importedComplaint->franceConnected)
            ->setAppointmentRequired($importedComplaint->appointmentRequired)
            ->setAppointmentContactInformation($importedComplaint->appointment?->appointmentContactText)
            ->setAppointmentAsked($importedComplaint->appointment?->appointmentAsked)
            ->setConsentContactElectronics($importedComplaint->identity->consentContactElectronics);

        // For Salesforce purpose, init the appointment journey without sending the appointment required email
        if (false === $complaint->isAppointmentRequired()) {
            $complaint->setAppointmentCancellationCounter(1);
        }

        if (null !== $importedComplaint->affectedService) {
            $complaint->setUnitAssigned($importedComplaint->affectedService);
        } else {
            $this->logger->error(sprintf('No affected service for complaint %s', $complaint->getFrontId()));

            throw new NoAffectedServiceException(sprintf('No affected service for complaint %s', $complaint->getFrontId()));
        }

        return $complaint;
    }
}
