<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\SendReport;

use App\Complaint\Exceptions\NoOodriveComplaintFolderException;
use App\Entity\Complaint;
use App\Oodrive\ApiClientInterface;
use App\Repository\ComplaintRepository;
use App\Salesforce\Messenger\ComplaintReportSend\ComplaintReportSendMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class SendReportHandler
{
    public function __construct(
        private readonly ApiClientInterface $oodriveClient,
        private readonly MessageBusInterface $bus,
        private readonly ComplaintRepository $complaintRepository
    ) {
    }

    /**
     * @throws NoOodriveComplaintFolderException
     */
    public function __invoke(SendReportMessage $message): void
    {
        /** @var Complaint $complaint */
        $complaint = $this->complaintRepository->find($message->getComplaintId());

        if (null === $complaint->getOodriveFolder()) {
            throw new NoOodriveComplaintFolderException("No Oodrive folder for complaint {$complaint->getId()}");
        }

        foreach ($message->getFiles() as $file) {
            $this->oodriveClient->uploadFile($file, $file->getClientOriginalName(), $complaint->getOodriveFolder());
        }

        $this->bus->dispatch(new ComplaintReportSendMessage($message->getComplaintId())); // Salesforce email
    }
}
