<?php

namespace App\Tests\Behat\MockedClasses;

use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\File as OodriveFile;
use App\Oodrive\DTO\Folder;
use App\Oodrive\ParamsObject\SearchParamObject;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FakeOodriveApiClient implements ApiClientInterface
{
    public function search(SearchParamObject $searchParamObject): array
    {
        return [];
    }

    public function getFolder(string $folderId): Folder
    {
        /* @phpstan-ignore-next-line */
        return new Folder(['id' => $folderId]);
    }

    public function createFolder(string $folderName, string $parentId): Folder
    {
        /* @phpstan-ignore-next-line */
        return new Folder(['id' => uniqid(), 'name' => $folderName]);
    }

    public function uploadFile(File|string $fileContent, string $fileName, string $parentId): OodriveFile
    {
        /* @phpstan-ignore-next-line */
        return new OodriveFile(['id' => uniqid(), 'name' => $fileName]);
    }

    public function uploadFileVersion(OodriveFile $file, File $fileContent): OodriveFile
    {
        return $file;
    }

    public function bulkUploadFiles(array $files, string $parentId): array
    {
        return [];
    }

    public function downloadFile(OodriveFile $file): ResponseInterface
    {
        $response = new MockResponse((string) file_get_contents(__DIR__.'/../Files/blank.pdf'));

        return (new MockHttpClient($response))->request('GET', sprintf('share/api/v1/io/items/%s', $file->getId()));
    }

    public function lockItem(string $itemId): bool
    {
        return true;
    }

    public function unlockItem(string $itemId): bool
    {
        return true;
    }

    public function isItemLocked(string $itemId): bool
    {
        return true;
    }

    public function getChildrenFolders(Folder $rootFolder): array
    {
        return [
            new Folder(['id' => uniqid(), 'name' => 'folder1', 'childFolderCount' => 0, 'isDir' => true, 'parentId' => $rootFolder->getId(), 'creationDate' => '2023-01-01']),
            new Folder(['id' => uniqid(), 'name' => 'folder2', 'childFolderCount' => 0, 'isDir' => true, 'parentId' => $rootFolder->getId(), 'creationDate' => '2023-01-01']),
        ];
    }

    public function getChildrenFiles(Folder $rootFolder): array
    {
        return [
            new OodriveFile(['id' => uniqid(), 'name' => 'file1', 'isDir' => false]),
            new OodriveFile(['id' => uniqid(), 'name' => 'file2', 'isDir' => false]),
        ];
    }
}
