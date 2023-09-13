<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DownloadAttachmentsApiControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private User $userGn;
    private User $userPn;

    /**
     * @throws FilesystemException
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        /** @var FilesystemOperator $defaultStorage */
        $defaultStorage = $container->get('default.storage');
        $defaultStorage->writeStream(
            'abcd-3002739-1/abcd-3002739-1.zip',
            fopen(self::$kernel->getProjectDir().'/tests/Behat/Files/abcd-3002739-1.zip', 'rb')
        );

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);
        /** @var User $userGn */
        $userGn = $userRepository->find(2);
        /** @var User $userPn */
        $userPn = $userRepository->find(3);
        $this->userGn = $userGn;
        $this->userPn = $userPn;
    }

    public function testDownloadAttachmentsSuccess(): void
    {
        $this->client->loginUser($this->userGn);
        $this->client->request(
            'GET',
            '/api/complaint/abcd-3002739-1/attachments',
        );

        $response = $this->client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/zip'));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDownloadAttachmentsNotLogged(): void
    {
        $this->client->request(
            'GET',
            '/api/complaint/abcd-3002739-1/attachments',
        );

        $response = $this->client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testDownloadAttachmentsLoggedWithWrongUnit(): void
    {
        $this->client->loginUser($this->userPn);
        $this->client->request(
            'GET',
            '/api/complaint/abcd-3002739-1/attachments',
        );

        $response = $this->client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDownloadAttachementsWhenZipNotExists(): void
    {
        $this->client->loginUser($this->userGn);
        $this->client->request(
            'GET',
            '/api/complaint/abcd-3002739-5/attachments',
        );

        $response = $this->client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());
    }
}
