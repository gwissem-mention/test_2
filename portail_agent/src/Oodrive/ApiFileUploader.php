<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Complaint\ComplaintWorkflowException;
use App\Complaint\ComplaintWorkflowManager;
use App\Complaint\Exceptions\NoOodriveComplaintFolderException;
use App\Entity\Complaint;
use App\Oodrive\Exception\FolderCreationException;
use App\Oodrive\Exception\OodriveErrorsEnum;
use App\Repository\ComplaintRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ApiFileUploader
{
    private const RECEPISSE = 'RECEPISSE';
    private const PV = 'PV';

    public function __construct(
        private readonly ApiClientInterface $oodriveClient,
        private readonly string $oodriveReportFolderName,
        private readonly ComplaintWorkflowManager $complaintWorkflowManager,
        private readonly ComplaintRepository $complaintRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws ComplaintWorkflowException|NoOodriveComplaintFolderException
     */
    public function upload(Complaint $complaint, UploadedFile $file, ?string $uploadType): bool
    {
        $isFileReplaced = false;
        $messages = [
            true => 'The file with type %s was successfully replaced.',
            false => 'The file with type %s has been uploaded successfully.',
        ];

        if (null === $complaint->getOodriveFolder()) {
            throw new NoOodriveComplaintFolderException("No Oodrive folder for complaint {$complaint->getId()}");
        }

        $complaintFolder = $this->oodriveClient->getFolder($complaint->getOodriveFolder());
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

        if (self::RECEPISSE === $uploadType) {
            $existingFiles = $this->oodriveClient->getChildrenFiles($complaintFolder);
            $existingFileNames = array_map(fn ($existingFile) => $existingFile->getName(), $existingFiles);
            $this->oodriveClient->uploadFile($file, $file->getClientOriginalName(), $reportFolder->getId());
            $isFileReplaced = true === in_array($file->getClientOriginalName(), $existingFileNames);
            $this->logger->info(sprintf($messages[$isFileReplaced], self::RECEPISSE));
        }

        if (self::PV === $uploadType) {
            $this->oodriveClient->uploadFile($file, $file->getClientOriginalName(), $reportFolder->getId());
            $this->logger->info(sprintf($messages[false], self::PV));
            $this->complaintWorkflowManager->closeAfterSendingTheReport($complaint);
            $this->complaintRepository->save($complaint->setClosedAt(new \DateTimeImmutable()), true);
        }

        return $isFileReplaced;
    }
}
