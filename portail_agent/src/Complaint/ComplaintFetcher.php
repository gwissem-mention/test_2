<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Complaint\Messenger\ComplaintFetch\ComplaintFetchMessage;
use App\Oodrive\ApiClientInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ComplaintFetcher
{
    public function __construct(
        private readonly ApiClientInterface $oodriveClient,
        private readonly string $oodriveRootFolderId,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function fetch(): int
    {
        $toFetchFolder = $this->oodriveClient->getFolder($this->oodriveRootFolderId);
        $complaintsFetchedCount = 0;
        $toFetchFolderChildren = $this->oodriveClient->getChildrenFolders($toFetchFolder);
        foreach ($toFetchFolderChildren as $childFolder) {
            $emailFolders = $this->oodriveClient->getChildrenFolders($childFolder);
            foreach ($emailFolders as $emailFolder) {
                $complaintFolders = $this->oodriveClient->getChildrenFolders($emailFolder);
                foreach ($complaintFolders as $complaintFolder) {
                    $this->bus->dispatch(new ComplaintFetchMessage($complaintFolder, $emailFolder));
                    ++$complaintsFetchedCount;
                }
            }
        }

        return $complaintsFetchedCount;
    }
}
