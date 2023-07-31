<?php

declare(strict_types=1);

namespace App\Oodrive\ReportsFetcher;

use App\Oodrive\DTO\ReportFolder;

interface ReportsFetcherInterface
{
    /**
     * @return array<ReportFolder>
     */
    public function fetch(string $email): array;
}
