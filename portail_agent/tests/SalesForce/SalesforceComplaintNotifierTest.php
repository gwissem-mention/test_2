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

    public function testWarmup(): void
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

        $salesForceComplaintNotifier->warmup($complaint);
    }
}
