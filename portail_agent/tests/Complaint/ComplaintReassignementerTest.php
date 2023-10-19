<?php

declare(strict_types=1);

namespace App\Tests\Complaint;

use App\AppEnum\Institution;
use App\Complaint\ComplaintReassignementer;
use App\Complaint\ComplaintWorkflowManager;
use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\Vehicle;
use App\Entity\Identity;
use App\Entity\User;
use App\Entity\Witness;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use App\Messenger\InformationCenter\InfocentreMessage;
use App\Notification\Messenger\UnitReassignement\AskUnitReassignementMessage;
use App\Notification\Messenger\UnitReassignement\UnitReassignementMessage;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use App\Salesforce\Messenger\ComplaintWarmup\ComplaintWarmupMessage;
use App\Salesforce\Messenger\UnitReassignment\UnitReassignmentMessage;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ComplaintReassignementerTest extends TestCase
{
    use ClockAwareTrait;

    public function testReassignAsSupervisor(): void
    {
        Clock::set(new MockClock());
        $envelope = new Envelope(new \stdClass());
        $user = new User('1234', Institution::GN);
        $complaint = $this->getComplaint();
        $security = $this->createMock(Security::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        $complaintWorkflowManager = $this->createMock(ComplaintWorkflowManager::class);
        $unitRepository = $this->createMock(UnitRepository::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(ApplicationTracesLogger::class);

        $security
            ->method('getUser')
            ->willReturn($user);

        $unitRepository
            ->method('findOneBy')
            ->willReturn($this->getUnit());

        $complaintReassignementer = new ComplaintReassignementer(
            $security,
            $entityManager,
            $messageBus,
            $complaintWorkflowManager,
            $unitRepository,
            $requestStack,
            $logger
        );

        $messageBus
            ->expects($this->at(0))
            ->method('dispatch')
            ->with(
                new UnitReassignmentMessage((int) $complaint->getId())
            )
            ->willReturn($envelope);

        $infocentreMessage = new InfocentreMessage(ApplicationTracesMessage::REDIRECT, $complaint, $this->getUnit());
        $messageBus
            ->expects($this->at(1))
            ->method('dispatch')
            ->with(
                $infocentreMessage
            )
            ->willReturn($envelope);

        $messageBus
            ->expects($this->at(2))
            ->method('dispatch')
            ->with(
                new UnitReassignementMessage($complaint, '103131', (bool) $complaint->isUnitReassignmentAsked(), $complaint->getAssignedTo())
            )
            ->willReturn($envelope);

        $messageBus
            ->expects($this->at(3))
            ->method('dispatch')
            ->with(
                new ComplaintWarmupMessage((int) $complaint->getId())
            )
            ->willReturn($envelope);

        $complaintReassignementer->reassignAsSupervisor($complaint, '103131', 'reassign text');

        $this->assertEquals($this->getDataInfocenterMessage(), $infocentreMessage->getData());
    }

    public function testaskReassignement(): void
    {
        Clock::set(new MockClock());
        $envelope = new Envelope(new \stdClass());
        $user = new User('1234', Institution::GN);
        $complaint = $this->getComplaint();
        $security = $this->createMock(Security::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        $complaintWorkflowManager = $this->createMock(ComplaintWorkflowManager::class);
        $unitRepository = $this->createMock(UnitRepository::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(ApplicationTracesLogger::class);

        $security
            ->method('getUser')
            ->willReturn($user);

        $unitRepository
            ->method('findOneBy')
            ->willReturn($this->getUnit());

        $complaintReassignementer = new ComplaintReassignementer(
            $security,
            $entityManager,
            $messageBus,
            $complaintWorkflowManager,
            $unitRepository,
            $requestStack,
            $logger
        );
        $infocentreMessage = new InfocentreMessage(ApplicationTracesMessage::REDIRECT, $complaint, $this->getUnit());

        $messageBus
            ->expects($this->at(0))
            ->method('dispatch')
            ->with($infocentreMessage)
            ->willReturn($envelope);

        $messageBus
            ->expects($this->at(1))
            ->method('dispatch')
            ->with(new AskUnitReassignementMessage($complaint, (string) $complaint->getUnitAssigned()))
            ->willReturn($envelope);

        $complaintReassignementer->askReassignement($complaint, '103131', 'reassign text');
        $this->assertEquals($this->getDataInfocenterMessage(), $infocentreMessage->getData());
    }

    private function getComplaint(): Complaint
    {
        return (new Complaint())
            ->setCreatedAt(new \DateTimeImmutable('2022-12-01'))
            ->setTest(true)
            ->setAppointmentDate(new \DateTimeImmutable('2022-12-03'))
            ->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING)
            ->setDeclarationNumber('PEL-2022-'.str_pad('1', 8, '0', STR_PAD_LEFT))
            ->setAlert('Alert de test trop longue')
            ->setIdentity(new Identity())
            ->setConsentContactEmail(true)
            ->setConsentContactSMS(true)
            ->setConsentContactPortal(true)
            ->setpersonLegalRepresented(new Identity())
            ->setFacts(new Facts())
            ->addObject(
                (new Vehicle())
                    ->setBrand('Citroën')
                    ->setModel('C3')
                    ->setRegistrationNumber('AA-123-AA')
                    ->setRegistrationCountry('France')
                    ->setInsuranceCompany('AXA')
                    ->setInsuranceNumber('1458R147R')
                    ->setNature('CAMION')
                    ->setDegradationDescription('Rétroviseur cassé')
                    ->setAmount(15000)
                    ->setStatus(AbstractObject::STATUS_DEGRADED)
            )
            ->setAdditionalInformation(
                (new AdditionalInformation())
                    ->setCctvPresent(AdditionalInformation::CCTV_PRESENT_YES)
                    ->setCctvAvailable(false)
                    ->setSuspectsKnown(true)
                    ->setSuspectsKnownText('2 hommes')
                    ->setWitnessesPresent(true)
                    ->setFsiVisit(true)
                    ->setObservationMade(true)
                    ->addWitness(
                        (new Witness())
                            ->setDescription('Jean Dupont')
                            ->setPhone('06 12 34 45 57')
                            ->setEmail('jean@example.com')
                    )
            );
    }

    private function getUnit(): Unit
    {
        return new Unit(
            'ddsp38-csp-voiron-ppel@interieur.gouv.fr',
            'ddsp38-ppel@interieur.gouv.fr',
            '103131',
            '103131',
            'Commissariat de police de Voiron',
            '45.362186',
            '5.59077',
            '114 cours Becquart Castelbon 38500 VOIRON',
            '38',
            '04 76 65 93 93',
            '24h/24 - 7j/7',
            '4083',
            Institution::PN
        );
    }

    /**
     * @return array<mixed>
     */
    private function getDataInfocenterMessage(): array
    {
        return [
            'declaration' => [
                'declarationNumber' => 'PEL-2022-00000001',
                'declarationStatus' => 'pel.assignment.pending',
                'declarationDate' => new \DateTimeImmutable('2022-12-01'),
                'refusalReason' => null,
                'withAlert' => true,
                'withAppointment' => true,
            ],
            'identity' => [
                'declarationTown' => null,
                'isFranceConnected' => false,
                'isPersonLegalRepresented' => true,
                'isVictime' => false,
                'isCorporationRepresented' => false,
            ],
            'facts' => [
                'placeNature' => null,
                'isVictimOfViolence' => false,
                'isStolenVehicle' => false,
            ],
            'affectation' => [
                'unitName' => 'Commissariat de police de Voiron',
                'unitCode' => '103131',
                'unitAddress' => '114 cours Becquart Castelbon 38500 VOIRON',
                'unitPhone' => '04 76 65 93 93',
                'serviceCode' => null,
                'agentCode' => null,
                'institution' => null,
            ],
            'action' => [
                'actionDate' => $this->now(),
                'action' => 'REORIONTATION',
            ],
        ];
    }
}
