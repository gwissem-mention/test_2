<?php

declare(strict_types=1);

namespace App\Controller;

use App\Oodrive\ApiClientInterface;
use App\Oodrive\OAuth2\Cache\TokensCacheInterface;
use App\Oodrive\OAuth2\GrantType\AuthorizationCodeGrantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function __invoke(ApiClientInterface $oodriveClient, AuthorizationCodeGrantType $grant, TokensCacheInterface $cache): Response
    {
        $first = $grant->getTokens();
        $refreshGrant = $grant->getRefreshTokenGrant($first->getRefreshToken());
        $second = $refreshGrant->getTokens();

        $cached1 = $cache->get($grant);
        $cached2 = $cache->get($grant);

        $fileContent = new File('/srv/citoyen/test.txt', true);
        $folder = $oodriveClient->createFolder('test_folder2', 'HcxbMKkGLFo');

        $file = $oodriveClient->uploadFile($fileContent, 'test_file1', $folder->getId());
        $fileUpdated = new \App\Oodrive\DTO\File(['id' => $file->getId(), 'name' => 'file_new_version.txt']);

        sleep(30);

        // $fileContent = new File('/srv/citoyen/test2.txt', true);
        // dump($fileUpdated);
        // $oodriveClient->uploadFileVersion($fileUpdated, $fileContent);
        // $oodriveClient->lockItem($file->getId());
        // $oodriveClient->unlockItem($file->getId());
        // $oodriveClient->isItemLocked($file->getId());

        $response = new Response();
        $response->setStatusCode(200);

        return $response;
    }
}
