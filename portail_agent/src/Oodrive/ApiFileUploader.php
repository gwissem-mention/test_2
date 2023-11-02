<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Complaint\ComplaintWorkflowException;
use App\Complaint\ComplaintWorkflowManager;
use App\Complaint\Exceptions\NoOodriveComplaintFolderException;
use App\Entity\Complaint;
use App\Entity\UploadReport;
use App\Repository\ComplaintRepository;
use App\Repository\UploadReportRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;

class ApiFileUploader
{
    private const PV = 'PV';

    public function __construct(
        private readonly ApiClientInterface $oodriveClient,
        private readonly ComplaintWorkflowManager $complaintWorkflowManager,
        private readonly ComplaintRepository $complaintRepository,
        private readonly LoggerInterface $logger,
        private readonly UploadReportRepository $uploadReportRepository,
        private readonly FolderResolver $folderResolver,
    ) {
    }

    /**
     * @throws ComplaintWorkflowException|NoOodriveComplaintFolderException
     */
    public function upload(
        Complaint $complaint,
        File $file,
        string $uploadType,
        int $timestamp,
        int $size,
        string $originName
    ): ApiFileUploaderStatusEnum {
        $messages = [
            true => 'The file with type %s was successfully replaced.',
            false => 'The file with type %s has been uploaded successfully.',
        ];

        $reportFolder = $this->folderResolver->resolveReportFolder($complaint);

        $fileAlreadyExist = $this->uploadReportRepository->uploadAlreadyExist(
            $complaint,
            $uploadType,
            $timestamp,
            $size,
            $originName
        );

        if ($fileAlreadyExist) {
            return ApiFileUploaderStatusEnum::IGNORED;
        }

        $isReplacement = $this->uploadReportRepository->mustBeReplaced(
            $complaint,
            $uploadType,
            $originName
        );

        $oodriveFile = $this->oodriveClient->uploadFile($file, $originName, $reportFolder->getId());

        if (self::PV === $uploadType) {
            if (!$isReplacement) {
                $this->complaintWorkflowManager->closeAfterSendingTheReport($complaint);
                $this->complaintRepository->save($complaint->setClosedAt(new \DateTimeImmutable()), true);
            } else {
                // @TODO: Notification Bis PV
            }
        }

        $this->logger->info(sprintf($messages[$isReplacement], $uploadType));

        $this->uploadReportRepository->save(new UploadReport(
            $oodriveFile->getId(),
            $timestamp,
            $size,
            $uploadType,
            $originName,
            $complaint
        ), true);

        return $isReplacement ? ApiFileUploaderStatusEnum::REPLACED : ApiFileUploaderStatusEnum::UPLOADED;
    }
}
