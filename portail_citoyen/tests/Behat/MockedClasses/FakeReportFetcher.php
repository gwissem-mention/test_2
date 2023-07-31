<?php

declare(strict_types=1);

namespace App\Tests\Behat\MockedClasses;

use App\Oodrive\DTO\File;
use App\Oodrive\DTO\ReportFolder;
use App\Oodrive\ReportsFetcher\ReportsFetcherInterface;

class FakeReportFetcher implements ReportsFetcherInterface
{
    public function fetch(string $email): array
    {
        return [
            new ReportFolder(
                'jpaWBwgWE9k',
                new \DateTimeImmutable('2023-01-01'),
                [
                    new File(['id' => 'slwS30-lxUE', 'name' => 'test1.pdf', 'isDir' => false]),
                    new File(['id' => 'X_LIEqrTLuk', 'name' => 'test2.pdf', 'isDir' => false]),
                ]
            ),
            new ReportFolder(
                'JItAgGjxVq6',
                new \DateTimeImmutable('2023-01-05'),
                [
                    new File(['id' => 'R^aiXR$#-T+', 'name' => 'test3.pdf', 'isDir' => false]),
                ]
            ),
        ];
    }
}
