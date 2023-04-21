<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Oodrive\DTO\File as OodriveFile;
use App\Oodrive\DTO\Folder;
use App\Oodrive\Event\PostBulkUploadFile;
use App\Oodrive\Event\PostCheckItemLock;
use App\Oodrive\Event\PostCreateFolder;
use App\Oodrive\Event\PostDeleteFolder;
use App\Oodrive\Event\PostDownloadFile;
use App\Oodrive\Event\PostGetChildrenFiles;
use App\Oodrive\Event\PostGetChildrenFolders;
use App\Oodrive\Event\PostGetFolder;
use App\Oodrive\Event\PostLockItem;
use App\Oodrive\Event\PostSearch;
use App\Oodrive\Event\PostUnlockItem;
use App\Oodrive\Event\PostUpdateFolder;
use App\Oodrive\Event\PostUploadFile;
use App\Oodrive\Event\PostUploadNewVersionFile;
use App\Oodrive\Event\PreBulkUploadFile;
use App\Oodrive\Event\PreCheckItemLock;
use App\Oodrive\Event\PreCreateFolder;
use App\Oodrive\Event\PreDeleteFolder;
use App\Oodrive\Event\PreDownloadFile;
use App\Oodrive\Event\PreGetChildrenFiles;
use App\Oodrive\Event\PreGetChildrenFolders;
use App\Oodrive\Event\PreGetFolder;
use App\Oodrive\Event\PreLockItem;
use App\Oodrive\Event\PreSearch;
use App\Oodrive\Event\PreUnlockItem;
use App\Oodrive\Event\PreUpdateFolder;
use App\Oodrive\Event\PreUploadFile;
use App\Oodrive\Event\PreUploadNewVersionFile;
use App\Oodrive\Exception\CheckItemLockException;
use App\Oodrive\Exception\ChildrenFilesFetchException;
use App\Oodrive\Exception\ChildrenFoldersFetchException;
use App\Oodrive\Exception\FileDownloadException;
use App\Oodrive\Exception\FileMetadataFetchException;
use App\Oodrive\Exception\FileUploadException;
use App\Oodrive\Exception\FolderCreationException;
use App\Oodrive\Exception\FolderUpdateException;
use App\Oodrive\Exception\ItemDeleteException;
use App\Oodrive\Exception\ItemLockException;
use App\Oodrive\Exception\ItemUnlockException;
use App\Oodrive\Exception\SearchException;
use App\Oodrive\OAuth2\OAuth2ClientInterface;
use App\Oodrive\ParamsObject\SearchParamObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    public function __construct(
        private readonly OAuth2ClientInterface $oodriveClient,
        private readonly LoggerInterface $oodriveLogger,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @return array<Folder|OodriveFile>
     */
    public function search(SearchParamObject $searchParamObject): array
    {
        $this->eventDispatcher->dispatch(new PreSearch($searchParamObject), PreSearch::NAME);
        $this->oodriveLogger->info(sprintf('Searching for %s', http_build_query($searchParamObject->asArray())));

        try {
            $response = $this->oodriveClient->request('GET', 'share/api/v1/search/items?'.$searchParamObject->asUrlPart(), [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw $e;
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf(
                'Failed search %s. Got status code %d',
                http_build_query($searchParamObject->asArray()),
                $response->getStatusCode()
            ));

            throw new SearchException($response);
        }

        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($responseJson) || empty($responseJson)) {
            $this->oodriveLogger->error(sprintf(
                'Unexpected API response while searching %s. Got status code %d',
                http_build_query($searchParamObject->asArray()),
                $response->getStatusCode()
            ), [$responseJson]);

            throw new SearchException($response);
        }

        $searchResult = array_map(function (array $item) {
            if ($item['$item']['isDir']) {
                return new Folder($item['$item']);
            }

            return new OodriveFile($item['$item']);
        }, $responseJson['$collection']);

        $this->eventDispatcher->dispatch(new PostSearch($searchResult), PostSearch::NAME);
        $this->oodriveLogger->info(sprintf('Searched for %s', http_build_query($searchParamObject->asArray())));

        return $searchResult;
    }

    public function getFolder(string $folderId): Folder
    {
        $this->eventDispatcher->dispatch(new PreGetFolder($folderId), PreGetFolder::NAME);
        $this->oodriveLogger->info(sprintf('Fetching folder with ID %s', $folderId));

        try {
            $folder = $this->doFetchFolderMetadata($folderId);
        } catch (FileMetadataFetchException $e) {
            /*
             * The API call succeded but did not return 201 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->eventDispatcher->dispatch(new PostGetFolder($folder), PostGetFolder::NAME);
        $this->oodriveLogger->info(sprintf('Fetched folder with ID %s', $folderId));

        return $folder;
    }

    public function createFolder(string $folderName, string $parentId): Folder
    {
        $this->eventDispatcher->dispatch(new PreCreateFolder($folderName, $parentId), PreCreateFolder::NAME);
        $this->oodriveLogger->info(sprintf('Creating a folder with name %s and parent %s', $folderName, $parentId));

        $response = $this->oodriveClient->request('POST', sprintf('share/api/v1/items/%s', $parentId), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode(['name' => $folderName, 'isDir' => true], JSON_THROW_ON_ERROR),
        ]);

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed creating folder with name %s. Got status code %d', $folderName, $response->getStatusCode()));

            throw new FolderCreationException($response);
        }

        // ... other checks eventually (when the forlder already exists for example)

        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($responseJson) || !isset($responseJson['$collection']) || empty($responseJson['$collection'])) {
            $this->oodriveLogger->error(sprintf('Unexpected API response while creating folder with name %s. Got status code %d', $folderName, $response->getStatusCode()), [$responseJson]);

            /* @TODO: Improve exception message there */
            throw new FolderCreationException($response);
        }

        $folder = new Folder($responseJson['$collection'][0]);

        $this->eventDispatcher->dispatch(new PostCreateFolder($folder), PostCreateFolder::NAME);
        $this->oodriveLogger->info(sprintf('Successfuly created a folder with name %s and parent %s and ID %s ', $folderName, $parentId, $folder->getId()));

        return $folder;
    }

    public function updateFolder(Folder $folder): Folder
    {
        $this->eventDispatcher->dispatch(new PreUpdateFolder($folder), PreUpdateFolder::NAME);
        $this->oodriveLogger->info(sprintf('Updating the folder metadata with name %s and parent %s', $folder->getName(), $folder->getParentId()));

        try {
            $folder = $this->doUpdateFolderMetadata($folder);
        } catch (FolderUpdateException $e) {
            /*
             * The API call succeded but did not return 200 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->oodriveLogger->info(sprintf('Successfully updated a folder with name %s and parent %s and ID %s ', $folder->getName(), $folder->getParentId(), $folder->getId()));
        $this->eventDispatcher->dispatch(new PostUpdateFolder($folder), PostUpdateFolder::NAME);

        return $folder;
    }

    public function deleteFolder(Folder $folder): Folder
    {
        $this->eventDispatcher->dispatch(new PreDeleteFolder($folder), PreDeleteFolder::NAME);
        $this->oodriveLogger->info(sprintf('Deleting the folder with name %s and parent %s', $folder->getName(), $folder->getParentId()));

        try {
            $this->doDeleteItem($folder->getId());
        } catch (ItemDeleteException $e) {
            /*
             * The API call succeded but did not return 202 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->oodriveLogger->info(sprintf('Successfuly deleted a folder with name %s and parent %s and ID %s ', $folder->getName(), $folder->getParentId(), $folder->getId()));
        $this->eventDispatcher->dispatch(new PostDeleteFolder($folder), PostDeleteFolder::NAME);

        return $folder;
    }

    public function uploadFile(File|string $fileContent, string $fileName, string $parentId): OodriveFile
    {
        $this->eventDispatcher->dispatch(new PreUploadFile($fileName, $parentId), PreUploadFile::NAME);
        $this->oodriveLogger->info(sprintf('Creating a file metadata with name %s and parent %s', $fileName, $parentId));
        try {
            $fileMetadata = $this->doCreateFileMetadata($fileName, $parentId);
        } catch (FileUploadException $e) {
            /*
             * The API call succeded but did not return 201 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->oodriveLogger->info(sprintf('Successfuly created a file metadata with name %s and parent %s and ID %s ', $fileName, $parentId, $fileMetadata->getId()));
        $this->oodriveLogger->info(sprintf('Uploading a file with name %s and id %s', $fileName, $fileMetadata->getId()));

        try {
            $uploaded = $this->doUploadFileContent($fileMetadata, $fileContent);
        } catch (FileUploadException $e) {
            /*
             * The API call succeded but did not return 201 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->oodriveLogger->info(sprintf('Successfuly uploaded a file with name %s and parent %s and ID %s ', $fileName, $parentId, $fileMetadata->getId()));
        $this->eventDispatcher->dispatch(new PostUploadFile($fileMetadata), PostUploadFile::NAME);

        return $fileMetadata;
    }

    public function uploadFileVersion(OodriveFile $file, File $fileContent): OodriveFile
    {
        $this->eventDispatcher->dispatch(new PreUploadNewVersionFile($file->getId()), PreUploadNewVersionFile::NAME);
        $this->oodriveLogger->info(sprintf('Uploading a new file version with id %s', $file->getId()));

        try {
            $fileMetadata = $this->doUpdateFileMetadata($file);
        } catch (FileMetadataFetchException $e) {
            /*
             * The API call succeded but did not return 200 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->oodriveLogger->info(sprintf('Successfuly fetched a file metadata with id %s', $file->getId()));
        $this->oodriveLogger->info(sprintf('Uploading a new version of file with name %s and id %s', $fileMetadata->getName(), $fileMetadata->getId()));

        try {
            $uploaded = $this->doUploadFileContent($fileMetadata, $fileContent);
        } catch (FileUploadException $e) {
            /*
             * The API call succeded but did not return 201 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->oodriveLogger->info(sprintf('Successfuly uploaded a new version of file with name %s and id %s', $fileMetadata->getName(), $fileMetadata->getId()));
        $this->eventDispatcher->dispatch(new PostUploadNewVersionFile($fileMetadata), PostUploadNewVersionFile::NAME);

        /* @phpstan-ignore-next-line */
        return new OodriveFile(['@todo', '@todo']);
    }

    /**
     * @param array<File> $files
     *
     * @return array<OodriveFile>
     */
    public function bulkUploadFiles(array $files, string $parentId): array
    {
        /* @TODO Find way to provider files informations */
        $this->eventDispatcher->dispatch(new PreBulkUploadFile($parentId), PreBulkUploadFile::NAME);
        $this->oodriveLogger->info(sprintf('Stating a bulk upload of files in parent %s', $parentId));

        $oodriveFiles = [];

        foreach ($files as $file) {
            /* @TODO we possibly can set a filename different */
            $oodriveFiles[] = $this->uploadFile($file, $file->getFilename(), $parentId);
        }

        $this->oodriveLogger->info(sprintf('Successfuly uploaded %d files in parent %s', count($oodriveFiles), $parentId));
        $this->eventDispatcher->dispatch(new PostBulkUploadFile($oodriveFiles), PostBulkUploadFile::NAME);

        return $oodriveFiles;
    }

    public function downloadFile(OodriveFile $file): ResponseInterface
    {
        $this->eventDispatcher->dispatch(new PreDownloadFile($file->getId()), PreDownloadFile::NAME);
        $this->oodriveLogger->info(sprintf('Download a file with id %s', $file->getId()));

        try {
            $response = $this->doDownloadFile($file);
        } catch (FileDownloadException $e) {
            /*
             * The API call succeded but did not return 200 OK
             * @TODO: May be retry ?
             */
            throw $e;
        } catch (\Exception $e) {
            /*
             * The API call did not succeed
             * @TODO: Mark the API as unavailable ? code stability issue ?
             */
            throw $e;
        }

        $this->oodriveLogger->info(sprintf('Successfully download a file with name %s and ID %s', $file->getName(), $file->getId()));
        $this->eventDispatcher->dispatch(new PostDownloadFile($file), PostDownloadFile::NAME);

        return $response;
    }

    public function lockItem(string $itemId): bool
    {
        $this->eventDispatcher->dispatch(new PreLockItem($itemId), PreLockItem::NAME);
        $this->oodriveLogger->info(sprintf('Locking item with id %s', $itemId));

        $response = $this->oodriveClient->request('PATCH', sprintf('share/api/v1/items/%s', $itemId), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode(['lock' => true], JSON_THROW_ON_ERROR),
        ]);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed locking item with id %s. Got status code %d', $itemId, $response->getStatusCode()));

            throw new ItemLockException($response);
        }

        // ... other checks eventually (when the forlder already exists for example)

        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->eventDispatcher->dispatch(new PostLockItem($itemId), PostLockItem::NAME);
        $this->oodriveLogger->info(sprintf('Successfuly locked item with id %s', $itemId));

        return true;
    }

    public function unlockItem(string $itemId): bool
    {
        $this->eventDispatcher->dispatch(new PreUnlockItem($itemId), PreUnlockItem::NAME);
        $this->oodriveLogger->info(sprintf('Unlocking item with id %s', $itemId));

        $response = $this->oodriveClient->request('PATCH', sprintf('share/api/v1/items/%s', $itemId), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode(['lock' => false], JSON_THROW_ON_ERROR),
        ]);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed Unlocking item with id %s. Got status code %d', $itemId, $response->getStatusCode()));

            throw new ItemUnlockException($response);
        }

        // ... other checks eventually (when the forlder already exists for example)

        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->eventDispatcher->dispatch(new PostUnlockItem($itemId), PostUnlockItem::NAME);
        $this->oodriveLogger->info(sprintf('Successfuly unlocked item with id %s', $itemId));

        return true;
    }

    public function isItemLocked(string $itemId): bool
    {
        $this->eventDispatcher->dispatch(new PreCheckItemLock($itemId), PreCheckItemLock::NAME);
        $this->oodriveLogger->info(sprintf('Checking if item with id %s is locked', $itemId));

        $response = $this->oodriveClient->request('GET', sprintf('share/api/v1/items/%s', $itemId), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed Unlocking item with id %s. Got status code %d', $itemId, $response->getStatusCode()));

            throw new CheckItemLockException($response);
        }

        // ... other checks eventually (when the forlder already exists for example)

        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($responseJson) || empty($responseJson)) {
            $this->oodriveLogger->error(sprintf('Unexpected API response while checking for item lock with id %s. Got status code %d', $itemId, $response->getStatusCode()), [$responseJson]);

            throw new FolderCreationException($response);
        }

        $this->eventDispatcher->dispatch(new PostCheckItemLock($itemId), PostCheckItemLock::NAME);
        $this->oodriveLogger->info(sprintf('Successfuly checked if item with id %s is locked', $itemId));

        return isset($responseJson['lock']);
    }

    /**
     * @return array<Folder>
     */
    public function getChildrenFolders(Folder $rootFolder): array
    {
        $this->eventDispatcher->dispatch(new PreGetChildrenFolders($rootFolder->getId()), PreGetChildrenFolders::NAME);
        $this->oodriveLogger->info(sprintf('Getting children folders of folder with id %s', $rootFolder->getId()));

        $response = $this->oodriveClient->request('GET', sprintf('share/api/v1/items/%s/children', $rootFolder->getId()), [
            'query' => [
                'type' => 'folders',
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed getting children folders of folder with id %s. Got status code %d', $rootFolder->getId(), $response->getStatusCode()));

            throw new ChildrenFoldersFetchException($response);
        }

        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($responseJson) || empty($responseJson)) {
            $this->oodriveLogger->error(sprintf('Unexpected API response while getting children folders of folder with id %s. Got status code %d', $rootFolder->getId(), $response->getStatusCode()), [$responseJson]);

            throw new FolderCreationException($response);
        }

        /* @phpstan-ignore-next-line */
        $folders = array_map(static fn (array $folderData) => new Folder($folderData), $responseJson['$collection']);
        $folders = array_filter($folders, static fn (Folder $folder) => $folder->isDir());

        $this->eventDispatcher->dispatch(
            new PostGetChildrenFolders($rootFolder->getId(), $folders),
            PostGetChildrenFolders::NAME
        );
        $this->oodriveLogger->info(sprintf('Successfuly got children folders of folder with id %s', $rootFolder->getId()));

        return $folders;
    }

    /**
     * @return array<OodriveFile>
     */
    public function getChildrenFiles(Folder $rootFolder): array
    {
        $this->eventDispatcher->dispatch(new PreGetChildrenFiles($rootFolder->getId()), PreGetChildrenFiles::NAME);
        $this->oodriveLogger->info(sprintf('Getting children files of folder with id %s', $rootFolder->getId()));

        $response = $this->oodriveClient->request('GET', sprintf('share/api/v1/items/%s/children', $rootFolder->getId()), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed getting children files of folder with id %s. Got status code %d', $rootFolder->getId(), $response->getStatusCode()));

            throw new ChildrenFilesFetchException($response);
        }

        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($responseJson) || empty($responseJson)) {
            $this->oodriveLogger->error(sprintf('Unexpected API response while getting children files of folder with id %s. Got status code %d', $rootFolder->getId(), $response->getStatusCode()), [$responseJson]);

            throw new FolderCreationException($response);
        }

        /* @phpstan-ignore-next-line */
        $files = array_map(static fn (array $fileData) => new OodriveFile($fileData), $responseJson['$collection']);
        $files = array_filter($files, static fn (OodriveFile $file) => !$file->isDir());

        $this->eventDispatcher->dispatch(
            new PostGetChildrenFiles($rootFolder->getId(), $files),
            PostGetChildrenFiles::NAME
        );
        $this->oodriveLogger->info(sprintf('Successfully got children files of folder with id %s', $rootFolder->getId()));

        return $files;
    }

    private function doFetchFolderMetadata(string $folderId): Folder
    {
        /* @phpstan-ignore-next-line */
        return new Folder($this->doFetchItemMetadata($folderId));
    }

    /** Not used for moment but probably will be in future */
    private function doFetchFileMetadata(string $fileId): OodriveFile
    {
        /* @phpstan-ignore-next-line */
        return new OodriveFile($this->doFetchItemMetadata($fileId));
    }

    /**
     * @return array<string>
     */
    private function doFetchItemMetadata(string $itemId): array
    {
        try {
            $response = $this->oodriveClient->request('GET', sprintf('share/api/v1/items/%s', $itemId), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed fetching file with id %s. Got error %s', $itemId, $e->getMessage()));

            throw $e;
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed fetching file with id %s. Got status code %d', $itemId, $response->getStatusCode()));

            throw new FileMetadataFetchException($response);
        }

        /** @var array<string> $responseJson */
        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return $responseJson;
    }

    private function doDeleteItem(string $itemId): void
    {
        try {
            $response = $this->oodriveClient->request('DELETE', sprintf('share/api/v1/items/%s', $itemId), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed deleting item with id %s. Got error %s', $itemId, $e->getMessage()));

            throw $e;
        }

        if (!in_array($response->getStatusCode(), [Response::HTTP_ACCEPTED, Response::HTTP_NO_CONTENT], true)) {
            $this->oodriveLogger->error(sprintf('Failed deleting file with id %s. Got status code %d', $itemId, $response->getStatusCode()));

            throw new ItemDeleteException($response);
        }
    }

    private function doCreateFileMetadata(string $fileName, string $parentId): OodriveFile
    {
        try {
            $response = $this->oodriveClient->request('POST', sprintf('share/api/v1/items/%s', $parentId), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode(['name' => $fileName, 'isDir' => false], JSON_THROW_ON_ERROR),
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed uploading file with name %s. Got error %s', $fileName, $e->getMessage()));

            throw $e;
        }

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed uploading file with name %s. Got status code %d', $fileName, $response->getStatusCode()));

            throw new FileUploadException($response);
        }

        // ... other checks eventually (when the file already exists for example)
        /** @var array<array<array<string>>> $responseJson */
        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        /* @phpstan-ignore-next-line */
        return new OodriveFile($responseJson['$collection'][0]);
    }

    private function doUpdateFileMetadata(OodriveFile $file): OodriveFile
    {
        try {
            $response = $this->oodriveClient->request('PATCH', sprintf('share/api/v1/items/%s', $file->getId()), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($file->getPayload(), JSON_THROW_ON_ERROR),
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed uploading file with name %s. Got error %s', $file->getName(), $e->getMessage()));

            throw $e;
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed uploading file with name %s. Got status code %d', $file->getName(), $response->getStatusCode()));

            throw new FileUploadException($response);
        }

        // ... other checks eventually (when the file already exists for example)
        /** @var array<string> $responseJson */
        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        /* @phpstan-ignore-next-line */
        return new OodriveFile($responseJson);
    }

    private function doUploadFileContent(OodriveFile $fileMetadata, File|string $fileContent): bool
    {
        if ($fileContent instanceof File) {
            $fileSize = $fileContent->getSize();
            $fileContent = $fileContent->getContent();
        } else {
            $fileSize = strlen($fileContent);
        }

        try {
            $response = $this->oodriveClient->request('PUT', sprintf('share/api/v1/io/items/%s', $fileMetadata->getId()), [
                'headers' => [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Length' => $fileSize,
                    'X-Upload-Id' => Uuid::v6(),
                    'Accept' => '*/*',
                ],
                'body' => $fileContent,
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed uploading file with ID %s. Got error %s', $fileMetadata->getId(), $e->getMessage()));

            throw $e;
        }

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed uploading file with id %s. Got status code %d', $fileMetadata->getId(), $response->getStatusCode()));

            throw new FileUploadException($response);
        }

        // ... other checks eventually (when the file already exists for example)

        $responseJson = $response->getContent();

        // ... Enrich eventually the File object with some additional metadata from the response

        return true;
    }

    private function doDownloadFile(OodriveFile $file): ResponseInterface
    {
        try {
            $response = $this->oodriveClient->request('GET', sprintf('share/api/v1/io/items/%s', $file->getId()), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed downloading file with name %s. Got error %s', $file->getName(), $e->getMessage()));

            throw $e;
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed downloading file with name %s. Got status code %d', $file->getName(), $response->getStatusCode()));

            throw new FolderUpdateException($response);
        }

        // ... other checks eventually (when the folder already exists for example)
        return $response;
    }

    private function doUpdateFolderMetadata(Folder $folder): Folder
    {
        try {
            $response = $this->oodriveClient->request('PATCH', sprintf('share/api/v1/items/%s', $folder->getId()), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($folder->getPayload(), JSON_THROW_ON_ERROR),
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed updating folder with name %s. Got error %s', $folder->getName(), $e->getMessage()));

            throw $e;
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed updating folder with name %s. Got status code %d', $folder->getName(), $response->getStatusCode()));

            throw new FolderUpdateException($response);
        }

        // ... other checks eventually (when the folder already exists for example)
        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        /* @phpstan-ignore-next-line */
        return new Folder($responseJson);
    }
}
