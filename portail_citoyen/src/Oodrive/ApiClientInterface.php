<?php

namespace App\Oodrive;

use App\Oodrive\DTO\File as OodriveFile;
use App\Oodrive\DTO\Folder;
use Symfony\Component\HttpFoundation\File\File;

interface ApiClientInterface
{
    public function createFolder(string $folderName, string $parentId): Folder;

    public function uploadFile(File $fileContent, string $fileName, string $parentName): OodriveFile;

    public function uploadFileVersion(OodriveFile $file, File $fileContent): OodriveFile;

    /**
     * @param array<File> $files
     *
     * @return array<OodriveFile>
     */
    public function bulkUploadFiles(array $files, string $parentId): array;

    public function lockItem(string $itemId): bool;

    public function unlockItem(string $itemId): bool;

    public function isItemLocked(string $itemId): bool;
}
