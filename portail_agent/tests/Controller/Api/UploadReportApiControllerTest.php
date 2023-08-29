<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadReportApiControllerTest extends WebTestCase
{
    public function testInvokePng(): void
    {
        $client = static::createClient();
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/iphone.png', 'iphone.png');

        $client->request(
            'POST',
            '/api/envoi-pv',
            ['url' => 'PEL-2023-00000115'],
            [$file]
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testInvokePdf(): void
    {
        $client = static::createClient();
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank.pdf');

        $client->request(
            'POST',
            '/api/envoi-pv',
            ['url' => 'PEL-2023-00000116'],
            [$file]
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testInvokeXls(): void
    {
        $client = static::createClient();
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.xls', 'blank.xls');

        $client->request(
            'POST',
            '/api/envoi-pv',
            ['url' => 'PEL-2023-00000117'],
            [$file]
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testInvokeMultipleFiles(): void
    {
        $client = static::createClient();
        $files = [
            new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/iphone.png', 'iphone.png'),
            new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank.pdf'),
        ];

        $client->request(
            'POST',
            '/api/envoi-pv',
            ['url' => 'PEL-2023-00000118'],
            $files
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testInvokeNoUrlParam(): void
    {
        $client = static::createClient();
        $file = new UploadedFile(self::$kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'blank.pdf');

        $client->request(
            'POST',
            '/api/envoi-pv',
            [],
            [$file]
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testInvokeNoFile(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/envoi-pv',
            ['url' => 'PEL-2023-00000119']
        );

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(400, $response->getStatusCode());
    }
}
