<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\SendReport;

use App\Complaint\Exceptions\NoOodriveComplaintFolderException;
use App\Entity\Complaint;
use App\Oodrive\ApiClientInterface;
use App\Oodrive\FolderResolver;
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
        private readonly ComplaintRepository $complaintRepository,
        private readonly FolderResolver $folderResolver,
    ) {
    }

    /**
     * @throws NoOodriveComplaintFolderException
     */
    public function __invoke(SendReportMessage $message): void
    {
        /** @var Complaint $complaint */
        $complaint = $this->complaintRepository->find($message->getComplaintId());

        $reportFolder = $this->folderResolver->resolveReportFolder($complaint);

        foreach ($message->getFiles() as $file) {
            $this->oodriveClient->uploadFile($file, $file->getClientOriginalName(), $reportFolder->getId());
        }

        $complaint->setOodriveReportFolder($reportFolder->getId());

        $this->complaintRepository->save($complaint, true);

        $this->bus->dispatch(new ComplaintReportSendMessage($message->getComplaintId(), count($message->getFiles()))); // Salesforce email
    }
}
