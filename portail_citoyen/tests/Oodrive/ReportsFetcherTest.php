<?php

declare(strict_types=1);

namespace App\Tests\Oodrive;

use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\File;
use App\Oodrive\DTO\Folder;
use App\Oodrive\ReportsFetcher\ReportsFetcher;
use PHPUnit\Framework\TestCase;

class ReportsFetcherTest extends TestCase
{
    public function testFetchFoundReports(): void
    {
        $mockOodrive = $this->createMock(ApiClientInterface::class);
        $mockOodrive
            ->expects($this->once())
            ->method('search')
            ->willReturn(
                [
                    new Folder(['id' => '75gVIUofzDA', 'name' => 'email@test.com', 'childFolderCount' => 1, 'isDir' => true, 'parentId' => 'kxP5HZEXlGI', 'creationDate' => '2023-01-01']),
                ]
            );

        $mockOodrive
            ->expects($this->once())
            ->method('getFolder')
            ->willReturn(new Folder(['id' => 'kxP5HZEXlGI', 'name' => '649b11d9f266e', 'parentId' => 'XeCnU6awkHc', 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]));

        $mockOodrive
            ->expects($this->exactly(3))
            ->method('getChildrenFolders')
            ->willReturnOnConsecutiveCalls(
                [
                    new Folder(['id' => 'I1L3LTGTjps', 'name' => '67198d2a-1509-11ee-bab0-5b472fc01b43', 'parentId' => '75gVIUofzDA', 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]),
                    new Folder(['id' => 'GvT36KDZknI', 'name' => '67198d2a-1509-11ee-bab0-5b472fc01b44', 'parentId' => '75gVIUofzDA', 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]),
                ],
                [
                    new Folder(['id' => '-40wbGIH_7I', 'name' => 'PV', 'parentId' => 'I1L3LTGTjps', 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]),
                ],
                [
                    new Folder(['id' => 'jpaWBwgWE9k', 'name' => 'PV', 'parentId' => 'GvT36KDZknI', 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]),
                ]
            );

        $mockOodrive
            ->expects($this->exactly(2))
            ->method('getChildrenFiles')
            ->willReturnOnConsecutiveCalls(
                [
                    new File(['id' => 'slwS30-lxUE', 'name' => 'test.pdf', 'parentId' => null, 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]),
                    new File(['id' => 'X_LIEqrTLuk', 'name' => 'test.jpg', 'parentId' => null, 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]),
                ],
                [
                    new File(['id' => 'slwS30-lxUA', 'name' => 'test.pdf', 'parentId' => null, 'creationDate' => '2023-01-01', 'childFolderCount' => 1, 'isDir' => true]),
                ],
            );

        $reportsFetcher = new ReportsFetcher($mockOodrive, 'XeCnU6awkHc', 'PV');

        $reports = $reportsFetcher->fetch('email@test.com');

        $this->assertCount(2, $reports);
        $this->assertEquals('-40wbGIH_7I', $reports[0]->getId());
        $this->assertCount(2, $reports[0]->getFiles());
        $this->assertEquals('slwS30-lxUE', $reports[0]->getFiles()[0]->getId());
        $this->assertEquals('X_LIEqrTLuk', $reports[0]->getFiles()[1]->getId());
        $this->assertEquals('jpaWBwgWE9k', $reports[1]->getId());
        $this->assertCount(1, $reports[1]->getFiles());
        $this->assertEquals('slwS30-lxUA', $reports[1]->getFiles()[0]->getId());
    }

    public function testFetchNotFoundReports(): void
    {
        $mockOodrive = $this->createMock(ApiClientInterface::class);
        $mockOodrive
            ->expects($this->once())
            ->method('search')
            ->willReturn(
                [
                    new Folder(['id' => '75gVIUofzDA', 'name' => 'email@test.com', 'childFolderCount' => 0, 'isDir' => true, 'parentId' => 'kxP5HZEXlGI', 'creationDate' => '2023-01-01']),
                ]
            );

        $reportsFetcher = new ReportsFetcher($mockOodrive, 'XeCnU6awkHc', 'PV');

        $reports = $reportsFetcher->fetch('email1@test.com');

        $this->assertEmpty($reports);
    }
}
