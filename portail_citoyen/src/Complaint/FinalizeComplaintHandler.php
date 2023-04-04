<?php

namespace App\Complaint;

use App\Generator\Complaint\ComplaintVaultGeneratorInterface;
use App\Oodrive\ApiClientInterface;
use App\Oodrive\FolderResolver;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FinalizeComplaintHandler
{
    public function __construct(
        private readonly FolderResolver $folderResolver,
        private readonly ApiClientInterface $oodriveClient,
        private readonly ComplaintVaultGeneratorInterface $generator,
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

        /** @var string $complaintJson */
        $complaintJson = $this->generator->generate($finalizeComplaint->getComplaint());

        $this->oodriveClient->uploadFile($complaintJson, 'plainte.json', $folder->getId());
    }
}
