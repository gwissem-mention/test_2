<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Oodrive\DTO\Folder;
use App\Oodrive\FolderRotation\FolderRotator;
use App\Oodrive\ParamsObject\SearchParamObject;

class FolderResolver
{
    public function __construct(
        private readonly FolderRotator $folderRotator,
        private readonly ApiClientInterface $oodriveClient,
        private string $oodriveRootFolderId,
    ) {
    }

    public function resolve(string $email, string $complaintNumber): Folder
    {
        $emailFolder = $this->getEmailFolder($email);

        return $this->oodriveClient->createFolder($complaintNumber, $emailFolder->getId());
    }

    private function getEmailFolder(string $email): Folder
    {
        $searchParam = (new SearchParamObject())->type(['folder'])->q($email);

        /** @var Folder[] $results */
        $results = $this->oodriveClient->search($searchParam);

        foreach ($results as $result) {
            if ($result->getName() === $email) {
                $parentFolder = $this->oodriveClient->getFolder($result->getParentId());

                if ($parentFolder->getId() === $this->oodriveRootFolderId) {
                    return $result;
                }
            }
        }

        $folder = $this->folderRotator->getLeastUsedFolder($this->oodriveRootFolderId);

        return $this->oodriveClient->createFolder($email, $folder->getId());
    }
}
