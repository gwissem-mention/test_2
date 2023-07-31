<?php

declare(strict_types=1);

namespace App\Tests\Oodrive;

use App\Oodrive\ApiClient;
use App\Oodrive\DTO\Folder;
use App\Oodrive\Exception\CheckItemLockException;
use App\Oodrive\Exception\FileUploadException;
use App\Oodrive\Exception\FolderCreationException;
use App\Oodrive\Exception\FolderUpdateException;
use App\Oodrive\Exception\ItemDeleteException;
use App\Oodrive\Exception\ItemLockException;
use App\Oodrive\Exception\ItemUnlockException;
use App\Oodrive\OAuth2\Cache\TokensCacheInterface;
use App\Oodrive\OAuth2\OAuth2ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\File\File;

class ApiClientTest extends TestCase
{
    public function testCreateFolder(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"$collection": [{"id": "hVorkkoZ", "name": "my_folder_name"}]}', ['http_code' => 201]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $folder = $apiClient->createFolder('my_folder_name', '123');

        $this->assertSame($folder->getId(), 'hVorkkoZ');
        $this->assertSame($folder->getName(), 'my_folder_name');
    }

    public function testCreateFolderWithHttpException(): void
    {
        $this->expectException(FolderCreationException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/items/123".'."\n002 : provided item name may have invalid character or forbidden item name like . or /");

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"code": "002", "description": "provided item name may have invalid character or forbidden item name like . or /"}', ['http_code' => 403]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->createFolder('my_folder_name', '123');
    }

    public function testCreateFolderWithNonJsonBodyException(): void
    {
        $this->expectException(\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('Not JSON', ['http_code' => 201]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->createFolder('my_folder_name', '123');
    }

    public function testCreateFolderWithInvalidJsonException(): void
    {
        $this->expectException(FolderCreationException::class);
        $this->expectExceptionMessage('HTTP 201 returned for "https://example.com/share/api/v1/items/123".');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"id": "jODsqi", "name": "my_folder_name"}', ['http_code' => 201]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->createFolder('my_folder_name', '123');
    }

    public function testUpdateFolder(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"id": "hVorkkoZ", "name": "my_folder_name", "parentId": "Etg5tfd"}', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $folder = new Folder([
            'id' => 'hVorkkoZ',
            'name' => 'my_folder_name',
            'parentId' => 'gtYhgfSD',
            'childFileCount' => 0,
            'childFolderCount' => 0,
            'isDir' => true,
        ]);
        $folder->setParentId('Etg5tfd');

        $updatedFolder = $apiClient->updateFolder($folder);

        $this->assertSame($updatedFolder->getId(), 'hVorkkoZ');
        $this->assertSame($updatedFolder->getName(), 'my_folder_name');
        $this->assertSame($updatedFolder->getParentId(), 'Etg5tfd');
    }

    public function testUpdateFolderWithHttpException(): void
    {
        $this->expectException(FolderUpdateException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/items/hVorkkoZ".'."\n002 : provided item name may have invalid character or forbidden item name like . or /");

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"code": "002", "description": "provided item name may have invalid character or forbidden item name like . or /"}', ['http_code' => 403]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $folder = new Folder([
            'id' => 'hVorkkoZ',
            'name' => 'my_folder_name',
            'parentId' => 'gtYhgfSD',
            'childFileCount' => 0,
            'childFolderCount' => 0,
            'isDir' => true,
        ]);
        $folder->setParentId('Etg5tfd');

        $updatedFolder = $apiClient->updateFolder($folder);
    }

    public function testUpdateFolderWithNonJsonBodyException(): void
    {
        $this->expectException(\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('Not JSON', ['http_code' => 200]),            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $folder = new Folder([
            'id' => 'hVorkkoZ',
            'name' => 'my_folder_name',
            'parentId' => 'gtYhgfSD',
            'childFileCount' => 0,
            'childFolderCount' => 0,
            'isDir' => true,
        ]);
        $folder->setParentId('Etg5tfd');

        $updatedFolder = $apiClient->updateFolder($folder);
    }

    public function testDeleteFolder(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('', ['http_code' => 202]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $folder = new Folder([
            'id' => 'hVorkkoZ',
            'name' => 'my_folder_name',
            'parentId' => 'gtYhgfSD',
            'childFileCount' => 0,
            'childFolderCount' => 0,
            'isDir' => true,
        ]);
        $folder = $apiClient->deleteFolder($folder);

        $this->assertSame($folder->getId(), 'hVorkkoZ');
        $this->assertSame($folder->getName(), 'my_folder_name');
    }

    public function testDeleteFolderWithHttpException(): void
    {
        $this->expectException(ItemDeleteException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/items/hVorkkoZ".'."\n009 : you do not have the permission to modify this item");

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"code": "009", "description": "you do not have the permission to modify this item"}', ['http_code' => 403]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $folder = new Folder([
            'id' => 'hVorkkoZ',
            'name' => 'my_folder_name',
            'parentId' => 'gtYhgfSD',
            'childFileCount' => 0,
            'childFolderCount' => 0,
            'isDir' => true,
        ]);

        $folder = $apiClient->deleteFolder($folder);
    }

    public function testUploadFile(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"$collection": [{"id": "aQSNdsin", "name": "testUploadFile.txt"}]}', ['http_code' => 201]),
                new MockResponse('', ['http_code' => 201]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $file = $apiClient->uploadFile(
            $this->getInMemoryFile('testUploadFile.txt', 'Hello world!'),
            'testUploadFile.txt',
            'folderParentId'
        );

        $this->assertSame($file->getId(), 'aQSNdsin');
        $this->assertSame($file->getName(), 'testUploadFile.txt');
    }

    public function testUploadFileWithHttpExceptionOnMetadata(): void
    {
        $this->expectException(FileUploadException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/items/folderParentId"');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('', ['http_code' => 403]),
                new MockResponse('', ['http_code' => 201]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $file = $apiClient->uploadFile(
            $this->getInMemoryFile('testUploadFile.txt', 'Hello world!'),
            'testUploadFile.txt',
            'folderParentId'
        );
    }

    public function testUploadFileWithJsonExceptionOnMetadata(): void
    {
        $this->expectException(\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('Not JSON', ['http_code' => 201]),
                new MockResponse('', ['http_code' => 201]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $file = $apiClient->uploadFile(
            $this->getInMemoryFile('testUploadFile.txt', 'Hello world!'),
            'testUploadFile.txt',
            'folderParentId'
        );
    }

    public function testUploadFileWithHttpExceptionOnUpload(): void
    {
        $this->expectException(FileUploadException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/io/items/aQSNdsin".');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"$collection": [{"id": "aQSNdsin", "name": "testUploadFile.txt"}]}', ['http_code' => 201]),
                new MockResponse('', ['http_code' => 403]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $file = $apiClient->uploadFile(
            $this->getInMemoryFile('testUploadFile.txt', 'Hello world!'),
            'testUploadFile.txt',
            'folderParentId'
        );
    }

    public function testLockItem(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"lock": {"creationDate": "2020-06-23T08:11:59Z", "isMine": true, "ownerId": "6994b85f-4a2c-406d-84f0-fbad16407c09"}}', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $this->assertTrue($apiClient->lockItem('itemId'));
    }

    public function testLockItemWithHttpException(): void
    {
        $this->expectException(ItemLockException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/items/itemId".');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('', ['http_code' => 403]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->lockItem('itemId');
    }

    public function testLockItemWithNonJsonBodyException(): void
    {
        $this->expectException(\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('Not JSON', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->lockItem('itemId');
    }

    public function testUnlockItem(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{}', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $this->assertTrue($apiClient->unlockItem('itemId'));
    }

    public function testUnlockItemWithHttpException(): void
    {
        $this->expectException(ItemUnlockException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/items/itemId".');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('', ['http_code' => 403]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->unlockItem('itemId');
    }

    public function testUnlockItemWithNonJsonBodyException(): void
    {
        $this->expectException(\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('Not JSON', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->unlockItem('itemId');
    }

    public function testIsLockedItemIsFalse(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"otherField": "abc"}', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $this->assertFalse($apiClient->isItemLocked('itemId'));
    }

    public function testIsLockedItemIsTrue(): void
    {
        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('{"lock": {"creationDate": "2020-06-23T08:11:59Z", "isMine": true, "ownerId": "6994b85f-4a2c-406d-84f0-fbad16407c09"}}', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $this->assertTrue($apiClient->isItemLocked('itemId'));
    }

    public function testIsLockedItemWithHttpException(): void
    {
        $this->expectException(CheckItemLockException::class);
        $this->expectExceptionMessage('HTTP 403 returned for "https://example.com/share/api/v1/items/itemId".');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('', ['http_code' => 403]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->isItemLocked('itemId');
    }

    public function testIsLockedItemWithNonJsonBodyException(): void
    {
        $this->expectException(\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $apiClient = new ApiClient(
            $this->getMockedOodriveClient([
                new MockResponse('Not JSON', ['http_code' => 200]),
            ]),
            new NullLogger(),
            new EventDispatcher(),
        );

        $apiClient->isItemLocked('itemId');
    }

    /* @phpstan-ignore-next-line */
    private function getMockedOodriveClient($responses): OAuth2ClientInterface
    {
        return new class($responses) extends MockHttpClient implements OAuth2ClientInterface {
            /* @phpstan-ignore-next-line */
            private TokensCacheInterface $cache;

            public function setCache(TokensCacheInterface $cache): OAuth2ClientInterface
            {
                $this->cache = $cache;

                return $this;
            }
        };
    }

    private function getInMemoryFile(string $filename, string $content): File
    {
        $lowLevelFilePath = sys_get_temp_dir().'/'.$filename;
        $lowLevelFile = fopen($lowLevelFilePath, 'w+');
        if ($lowLevelFile) {
            fwrite($lowLevelFile, $content);
            fclose($lowLevelFile);
        }

        return new File($lowLevelFilePath);
    }
}
