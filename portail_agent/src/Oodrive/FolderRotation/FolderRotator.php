<?php

namespace App\Oodrive\FolderRotation;

use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\Folder;

class FolderRotator
{
    public function __construct(
        private ApiClientInterface $oodriveClient,
        private FolderNameGenerator $folderNameGenerator,
        private int $folderRotationCount,
        private int $folderRotationTreshold,
    ) {
    }

    public function initFolders(string $folderId, int $folderCount): void
    {
        $rootFolder = $this->oodriveClient->getFolder($folderId);

        /** @var Folder[] $children */
        $children = $this->oodriveClient->getChildrenFolders($rootFolder);
        $missingFolder = $folderCount - count($children);

        if ($missingFolder > 0) {
            $this->createStructure($folderId, $missingFolder);
        }
    }

    public function getLeastUsedFolder(string $parentFolder): Folder
    {
        /** @phpstan-ignore-next-line */
        $children = $this->oodriveClient->getChildrenFolders(new Folder(['id' => $parentFolder]));
        usort($children, fn (Folder $a, Folder $b) => $a->getChildrenFolderCount() <=> $b->getChildrenFolderCount());

        foreach ($children as $child) {
            if ($child->getChildrenFolderCount() < $this->folderRotationTreshold) {
                return $child;
            }
        }

        $this->createStructure($parentFolder, $this->folderRotationCount);

        return $this->getLeastUsedFolder($parentFolder);
    }

    private function createStructure(string $folderId, int $missingFolder): void
    {
        $rootFolder = $this->oodriveClient->getFolder($folderId);

        for ($i = 0; $i < $missingFolder; ++$i) {
            $this->oodriveClient->createFolder($this->folderNameGenerator->generateName(), $rootFolder->getId());
        }
    }
}
