<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Generator\Complaint\ComplaintVaultGeneratorInterface;
use App\Oodrive\ApiClientInterface;
use App\Oodrive\FolderResolver;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FinalizeComplaintHandler
{
    public function __construct(
        private readonly FolderResolver $folderResolver,
        private readonly ApiClientInterface $oodriveClient,
        private readonly ComplaintVaultGeneratorInterface $generator,
        private readonly FilesystemOperator $defaultStorage
    ) {
    }

    public function __invoke(FinalizeComplaint $finalizeComplaint): void
    {
        $email = $finalizeComplaint->getComplaint()->getIdentity()?->getContactInformation()?->getEmail();

        if (!$email) {
            throw new \Exception('Email is required');
        }

        $complaintId = $finalizeComplaint->getComplaint()->getId()->toRfc4122();

        $folder = $this->folderResolver->resolve($email, $complaintId);

        $complaint = $finalizeComplaint->getComplaint();

        /** @var string $complaintJson */
        $complaintJson = $this->generator->generate($complaint);

        $this->oodriveClient->uploadFile($complaintJson, 'plainte.json', $folder->getId());

        if ($complaint->getObjects() && !$complaint->getObjects()->getObjects()->isEmpty()) {
            foreach ($complaint->getObjects()->getObjects() as $object) {
                foreach ($object->getFiles() as $file) {
                    $this->oodriveClient->uploadFile(
                        $this->defaultStorage->read($file->getPath()),
                        $file->getName(),
                        $folder->getId()
                    );
                    $this->defaultStorage->delete($file->getPath());
                }
            }
        }
    }
}
