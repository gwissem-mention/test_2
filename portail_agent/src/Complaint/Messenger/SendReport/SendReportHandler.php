<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\SendReport;

use App\Complaint\Exceptions\NoOodriveComplaintFolderException;
use App\Entity\Complaint;
use App\Oodrive\ApiClientInterface;
use App\Oodrive\Exception\FolderCreationException;
use App\Oodrive\Exception\OodriveErrorsEnum;
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
        private readonly string $oodriveReportFolderName
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

        try {
            $reportFolder = $this->oodriveClient->createFolder($this->oodriveReportFolderName, $complaint->getOodriveFolder());
        } catch (FolderCreationException $exception) {
            if (OodriveErrorsEnum::NAME_ALREADY_EXIST === $exception->getErrorCode()) {
                $complaintFolder = $this->oodriveClient->getFolder($complaint->getOodriveFolder());
                $complaintFolderChildren = $this->oodriveClient->getChildrenFolders($complaintFolder);
                foreach ($complaintFolderChildren as $child) {
                    if ($this->oodriveReportFolderName === $child->getName()) {
                        $reportFolder = $child;
                        break;
                    }
                }

                if (!isset($reportFolder)) {
                    throw $exception;
                }
            } else {
                throw $exception;
            }
        }

        foreach ($message->getFiles() as $file) {
            $this->oodriveClient->uploadFile($file, $file->getClientOriginalName(), $reportFolder->getId());
        }

        $complaint->setOodriveReportFolder($reportFolder->getId());

        $this->complaintRepository->save($complaint, true);

        $this->bus->dispatch(new ComplaintReportSendMessage($message->getComplaintId())); // Salesforce email
    }
}
