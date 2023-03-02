<?php

declare(strict_types=1);

namespace App\Etalab;

interface EtalabApiClientInterface
{
    /**
     * @return array<string, mixed>
     */
    public function search(string $query, int $limit): array;
}
