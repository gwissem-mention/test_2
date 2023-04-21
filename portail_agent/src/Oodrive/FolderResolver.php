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
        $searchParam = (new SearchParamObject())->type(['folder'])->folderId($this->oodriveRootFolderId)->q($email);

        $parentFolders = $this->oodriveClient->getChildrenFolders($this->oodriveClient->getFolder($this->oodriveRootFolderId));
        $parentFoldersId = array_map(static fn (Folder $folderData) => $folderData->getId(), $parentFolders);

        /** @var Folder[] $results */
        $results = $this->oodriveClient->search($searchParam);
        $results = array_filter($results, static fn (Folder $folder) => in_array($folder->getParentId(), $parentFoldersId));

        if (count($results) > 0) {
            return reset($results);
        }

        $folder = $this->folderRotator->getLeastUsedFolder($this->oodriveRootFolderId);

        return $this->oodriveClient->createFolder($email, $folder->getId());
    }
}
