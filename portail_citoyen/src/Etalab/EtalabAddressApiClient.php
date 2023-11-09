<?php

declare(strict_types=1);

namespace App\Etalab;

use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EtalabAddressApiClient implements EtalabApiClientInterface
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function search(string $query, int $limit = 5): array
    {
        $response = $this->client->request('GET', 'https://api-adresse.data.gouv.fr/search/', [
            'query' => [
                'q' => $query,
                'limit' => $limit,
            ],
        ]);

        try {
            return $response->toArray();
        } catch (HttpExceptionInterface) {
            return [];
        }
    }
}
