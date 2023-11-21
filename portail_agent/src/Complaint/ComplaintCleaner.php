<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Entity\AttachmentDownload;
use App\Entity\Complaint;
use App\Repository\AttachmentDownloadRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;

class ComplaintCleaner
{
    public function __construct(
        private readonly AttachmentDownloadRepository $attachmentDownloadRepository,
        private readonly FilesystemOperator $defaultStorage,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function zipAttachmentsCleaner(): int
    {
        /** @var array<AttachmentDownload> $attachmentsToClean */
        $attachmentsToClean = $this->attachmentDownloadRepository->findAttachmentsToClean();

        foreach ($attachmentsToClean as $attachment) {
            try {
                /** @var Complaint $complaint */
                $complaint = $attachment->getComplaint();

                $zipAttachmentFile = $complaint->getFrontId().'/'.$complaint->getFrontId().'.zip';

                if ($this->defaultStorage->has($zipAttachmentFile)) {
                    $this->defaultStorage->delete($zipAttachmentFile);
                }

                $this->attachmentDownloadRepository->save($attachment->setCleanedAt(new \DateTimeImmutable('now')));
            } catch (\Exception $exception) {
                $this->logger->error(sprintf('Error while cleaning up zip attachments files for complaint %s: %s', $attachment->getComplaint()?->getId(), $exception->getMessage()));
            }
        }

        $this->entityManager->flush();

        return count($attachmentsToClean);
    }
}
