<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class OodriveCleaner
{
    public function __construct(
        private readonly ComplaintRepository $complaintRepository,
        private readonly ApiClientInterface $oodriveClient,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly string $oodriveComplaintFilename
    ) {
    }

    public function attachmentsCleaner(): int
    {
        /** @var array<Complaint> $complaints */
        $complaints = $this->complaintRepository->findComplaintsForOodriveAttachmentsOnlyCleanUp();

        foreach ($complaints as $complaint) {
            try {
                $folder = $this->oodriveClient->getFolder((string) $complaint->getOodriveFolder());
                $this->oodriveClient->getChildrenFiles($folder);

                foreach ($this->oodriveClient->getChildrenFiles($folder) as $file) {
                    if ($this->oodriveComplaintFilename !== $file->getName()) {
                        $this->oodriveClient->deleteFile($file);
                    }
                }

                $this->complaintRepository->save($complaint->setOodriveCleanedUpAttachmentsAt(new \DateTimeImmutable()));
            } catch (\Exception $exception) {
                $this->logger->error(sprintf('Error while cleaning up oodrive files for complaint %s: %s', $complaint->getId(), $exception->getMessage()));
            }
        }

        $this->entityManager->flush();

        return count($complaints);
    }

    public function declarationCleaner(): int
    {
        /** @var array<Complaint> $complaints */
        $complaints = $this->complaintRepository->findComplaintsForOodriveDeclarationCleanUp();

        foreach ($complaints as $complaint) {
            try {
                $folder = $this->oodriveClient->getFolder((string) $complaint->getOodriveFolder());
                $this->oodriveClient->getChildrenFiles($folder);

                foreach ($this->oodriveClient->getChildrenFiles($folder) as $file) {
                    $this->oodriveClient->deleteFile($file);
                }

                $this->complaintRepository->save($complaint->setOodriveCleanedUpDeclarationAt(new \DateTimeImmutable()));
            } catch (\Exception $exception) {
                $this->logger->error(sprintf('Error while cleaning up oodrive files for complaint %s: %s', $complaint->getId(), $exception->getMessage()));
            }
        }

        $this->entityManager->flush();

        return count($complaints);
    }

    public function reportCleaner(): int
    {
        /** @var array<Complaint> $complaints */
        $complaints = $this->complaintRepository->findComplaintsForOodriveReportCleanUp();

        foreach ($complaints as $complaint) {
            try {
                $this->oodriveClient->deleteFolder($this->oodriveClient->getFolder((string) $complaint->getOodriveReportFolder()));
                $this->complaintRepository->save($complaint->setOodriveCleanedUpReportAt(new \DateTimeImmutable()));
            } catch (\Exception $exception) {
                $this->logger->error(sprintf('Error while cleaning up oodrive files for complaint %s: %s', $complaint->getId(), $exception->getMessage()));
            }
        }

        $this->entityManager->flush();

        return count($complaints);
    }
}
