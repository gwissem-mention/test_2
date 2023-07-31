<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Oodrive\DTO\File as OodriveFile;
use App\Oodrive\DTO\Folder;
use App\Oodrive\ParamsObject\SearchParamObject;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiClientInterface
{
    /**
     * @return array<OodriveFile|Folder>
     */
    public function search(SearchParamObject $searchParamObject): array;

    public function getFolder(string $folderId): Folder;

    public function createFolder(string $folderName, string $parentId): Folder;

    public function uploadFile(File|string $fileContent, string $fileName, string $parentId): OodriveFile;

    public function uploadFileVersion(OodriveFile $file, File $fileContent): OodriveFile;

    /**
     * @param array<File> $files
     *
     * @return array<OodriveFile>
     */
    public function bulkUploadFiles(array $files, string $parentId): array;

    public function downloadFile(OodriveFile $file): ResponseInterface;

    public function lockItem(string $itemId): bool;

    public function unlockItem(string $itemId): bool;

    public function isItemLocked(string $itemId): bool;

    /**
     * @return array<Folder>
     */
    public function getChildrenFolders(Folder $rootFolder): array;

    /**
     * @return array<OodriveFile>
     */
    public function getChildrenFiles(Folder $rootFolder): array;
}
