<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Oodrive\DTO\File as OodriveFile;
use App\Oodrive\DTO\Folder;
use App\Oodrive\Event\PostBulkUploadFile;
use App\Oodrive\Event\PostCheckItemLock;
use App\Oodrive\Event\PostCreateFolder;
use App\Oodrive\Event\PostLockItem;
use App\Oodrive\Event\PostUnlockItem;
use App\Oodrive\Event\PostUploadFile;
use App\Oodrive\Event\PostUploadNewVersionFile;
use App\Oodrive\Event\PreBulkUploadFile;
use App\Oodrive\Event\PreCheckItemLock;
use App\Oodrive\Event\PreCreateFolder;
use App\Oodrive\Event\PreLockItem;
use App\Oodrive\Event\PreUnlockItem;
use App\Oodrive\Event\PreUploadFile;
use App\Oodrive\Event\PreUploadNewVersionFile;
use App\Oodrive\Exception\CheckItemLockException;
use App\Oodrive\Exception\FileMetadataFetchException;
use App\Oodrive\Exception\FileUploadException;
use App\Oodrive\Exception\FolderCreationException;
use App\Oodrive\Exception\ItemLockException;
use App\Oodrive\Exception\ItemUnlockException;
use App\Oodrive\OAuth2\OAuth2ClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiClient implements ApiClientInterface
{
    public function __construct(
        private readonly OAuth2ClientInterface $oodriveClient,
        private readonly LoggerInterface $oodriveLogger,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
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

    public function uploadFile(File $fileContent, string $fileName, string $parentId): OodriveFile
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

    /** Not used for moment but probably will be in future */
    /* @phpstan-ignore-next-line */
    private function doFetchFileMetadata(string $fileId): OodriveFile
    {
        try {
            $response = $this->oodriveClient->request('GET', sprintf('share/api/v1/items/%s', $fileId), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            $this->oodriveLogger->error(sprintf('Failed fetching file with id %s. Got error %s', $fileId, $e->getMessage()));

            throw $e;
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $this->oodriveLogger->error(sprintf('Failed fetching file with id %s. Got status code %d', $fileId, $response->getStatusCode()));

            throw new FileMetadataFetchException($response);
        }

        /** @var array<string> $responseJson */
        $responseJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return new OodriveFile($responseJson);
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

        return new OodriveFile($responseJson);
    }

    private function doUploadFileContent(OodriveFile $fileMetadata, File $fileContent): bool
    {
        try {
            $response = $this->oodriveClient->request('PUT', sprintf('share/api/v1/io/items/%s', $fileMetadata->getId()), [
                'headers' => [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Length' => $fileContent->getSize(),
                    'X-Upload-Id' => Uuid::v6(),
                    'Accept' => '*/*',
                ],
                'body' => $fileContent->getContent(),
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
}
