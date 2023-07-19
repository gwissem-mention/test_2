<?php

namespace App\Complaint;

use App\Complaint\Exceptions\NoAffectedServiceException;
use App\Complaint\Parser\AdditionalInformationParser;
use App\Complaint\Parser\CorporationParser;
use App\Complaint\Parser\FactsParser;
use App\Complaint\Parser\IdentityParser;
use App\Complaint\Parser\ObjectsParser;
use App\Entity\Complaint;
use App\Referential\Repository\UnitRepository;
use Psr\Log\LoggerInterface;

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
        /** @var object $complaintJson */
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
        foreach ($complaintJson->objects as $objects) {
            foreach ($objects as $object) {
                $complaint->addObject($this->objectsParser->parse($object));
            }
        }

        return $complaint;
    }

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
            ->setAppointmentContactInformation($importedComplaint->appointment->appointmentContactText)
            ->setConsentContactElectronics($importedComplaint->identity->consentContactElectronics);

        if (!is_null($importedComplaint->affectedService)) {
            $complaint->setUnitAssigned($importedComplaint->affectedService);
        } else {
            $this->logger->error(sprintf('No affected service for complaint %s', $complaint->getFrontId()));

            throw new NoAffectedServiceException(sprintf('No affected service for complaint %s', $complaint->getFrontId()));
        }

        return $complaint;
    }
}
