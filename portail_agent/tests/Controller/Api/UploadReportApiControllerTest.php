<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadReportApiControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private User $userGn;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        /** @var User $userGn */
        $userGn = $userRepository->find(2);
        $this->userGn = $userGn;
    }

    public function testSuccessfulFileReplaced(): void
    {
        parent::setUp();

        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'file1');

        $this->client->loginUser($this->userGn);

        $this->client->request(
            'PUT',
            '/api/complaint/2300000112/lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'RECEPISSE',
                'HTTP_timestamp' => 1696421622,
                'HTTP_size' => 1000,
                'HTTP_originName' => 'test.png',
            ],
            base64_encode($file->getContent()),
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testSuccessfulFileUpload(): void
    {
        parent::setUp();
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank');

        $this->client->loginUser($this->userGn);

        $this->client->request(
            'PUT',
            '/api/complaint/2300000120/lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'PV',
                'HTTP_timestamp' => 1696421622,
                'HTTP_size' => 1000,
                'HTTP_originName' => 'test.png',
            ],
            base64_encode($file->getContent()),
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testUnauthorizedUser(): void
    {
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank');

        $this->client->request(
            'PUT',
            '/api/complaint/he file has been uploaded successfully./lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'PV',
                'HTTP_timestamp' => 1696421622,
                'HTTP_size' => 1000,
                'HTTP_originName' => 'test.png',
            ],
            base64_encode($file->getContent()),
        );
        $this->assertResponseStatusCodeSame(401);
    }

    public function testForbiddenAccessForComplaint(): void
    {
        parent::setUp();
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank');

        $this->client->loginUser($this->userGn);

        $this->client->request(
            'PUT',
            '/api/complaint/2300000125/lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'PV',
                'HTTP_timestamp' => 1696421622,
                'HTTP_size' => 1000,
                'HTTP_originName' => 'test.png',
            ],
            base64_encode($file->getContent()),
        );

        $this->assertResponseStatusCodeSame(403);
    }

    public function testInvalidUploadType(): void
    {
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank');

        $this->client->loginUser($this->userGn);

        $this->client->request(
            'PUT',
            '/api/complaint/2300000030/lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'TEST',
                'HTTP_timestamp' => 1696421622,
                'HTTP_size' => 1000,
                'HTTP_originName' => 'test.png',
            ],
            base64_encode($file->getContent()),
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testNoTimestamp(): void
    {
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank');

        $this->client->loginUser($this->userGn);

        $this->client->request(
            'PUT',
            '/api/complaint/2300000030/lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'TEST',
                'HTTP_size' => 1000,
                'HTTP_originName' => 'test.png',
            ],
            base64_encode($file->getContent()),
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testNoSize(): void
    {
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank');

        $this->client->loginUser($this->userGn);

        $this->client->request(
            'PUT',
            '/api/complaint/2300000030/lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'TEST',
                'HTTP_timestamp' => 1696421622,
                'HTTP_originName' => 'test.png',
            ],
            base64_encode($file->getContent()),
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testNoOriginName(): void
    {
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank');

        $this->client->loginUser($this->userGn);

        $this->client->request(
            'PUT',
            '/api/complaint/2300000030/lrp-upload',
            [],
            [],
            [
                'HTTP_X-UPLOAD-TYPE' => 'TEST',
                'HTTP_timestamp' => 1696421622,
                'HTTP_size' => 1000,
            ],
            base64_encode($file->getContent()),
        );

        $this->assertResponseStatusCodeSame(400);
    }
}
