<?php

declare(strict_types=1);

namespace App\Tests\SalesForce;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use App\Salesforce\SalesForceComplaintNotifier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SalesforceComplaintNotifierTest extends KernelTestCase
{
    public function testStartJourney(): void
    {
        // If startJourney throw an exception, the test will fail
        $this->expectNotToPerformAssertions();

        self::bootKernel();
        $container = static::getContainer();

        /** @var SalesForceComplaintNotifier $salesForceComplaintNotifier */
        $salesForceComplaintNotifier = $container->get(SalesForceComplaintNotifier::class);

        /** @var ComplaintRepository $complaintRepo */
        $complaintRepo = $container->get(ComplaintRepository::class);

        /** @var Complaint $complaint */
        $complaint = $complaintRepo->find(1);

        $salesForceComplaintNotifier->startJourney($complaint);
    }

    public function testAssigment(): void
    {
        // If warmup throw an exception, the test will fail
        $this->expectNotToPerformAssertions();

        self::bootKernel();
        $container = static::getContainer();

        /** @var SalesForceComplaintNotifier $salesForceComplaintNotifier */
        $salesForceComplaintNotifier = $container->get(SalesForceComplaintNotifier::class);

        /** @var ComplaintRepository $complaintRepo */
        $complaintRepo = $container->get(ComplaintRepository::class);

        /** @var Complaint $complaint */
        $complaint = $complaintRepo->find(1);

        $salesForceComplaintNotifier->assignment($complaint);
    }

    public function testAppointmentDone(): void
    {
        // If warmup throw an exception, the test will fail
        $this->expectNotToPerformAssertions();

        self::bootKernel();
        $container = static::getContainer();

        /** @var SalesForceComplaintNotifier $salesForceComplaintNotifier */
        $salesForceComplaintNotifier = $container->get(SalesForceComplaintNotifier::class);

        /** @var ComplaintRepository $complaintRepo */
        $complaintRepo = $container->get(ComplaintRepository::class);

        /** @var Complaint $complaint */
        $complaint = $complaintRepo->find(1);

        $salesForceComplaintNotifier->appointmentDone($complaint);
    }

    public function testReportSent(): void
    {
        // If warmup throw an exception, the test will fail
        $this->expectNotToPerformAssertions();

        self::bootKernel();
        $container = static::getContainer();

        /** @var SalesForceComplaintNotifier $salesForceComplaintNotifier */
        $salesForceComplaintNotifier = $container->get(SalesForceComplaintNotifier::class);

        /** @var ComplaintRepository $complaintRepo */
        $complaintRepo = $container->get(ComplaintRepository::class);

        /** @var Complaint $complaint */
        $complaint = $complaintRepo->find(1);

        $salesForceComplaintNotifier->reportSent($complaint, 1);
    }

    public function testUnitReassignment(): void
    {
        // If warmup throw an exception, the test will fail
        $this->expectNotToPerformAssertions();

        self::bootKernel();
        $container = static::getContainer();

        /** @var SalesForceComplaintNotifier $salesForceComplaintNotifier */
        $salesForceComplaintNotifier = $container->get(SalesForceComplaintNotifier::class);

        /** @var ComplaintRepository $complaintRepo */
        $complaintRepo = $container->get(ComplaintRepository::class);

        /** @var Complaint $complaint */
        $complaint = $complaintRepo->find(1);

        $salesForceComplaintNotifier->unitReassignment($complaint);
    }
}
