<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\SendReport;

use App\Oodrive\ApiClientInterface;
use App\Salesforce\Messenger\ComplaintReportSend\ComplaintReportSendMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class SendReportHandler
{
    public function __construct(
        private readonly ApiClientInterface $oodriveClient,
        private readonly string $tmpComplaintFolderId,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function __invoke(SendReportMessage $message): void
    {
        // TODO: make this dynamic based on the complaint, when we know the complaint id
        $this->oodriveClient->uploadFile($message->getReport(), 'PV.pdf', $this->tmpComplaintFolderId);

        $this->bus->dispatch(new ComplaintReportSendMessage($message->getComplaintId())); // Salesforce email
    }
}
