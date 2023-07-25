<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient;

use App\Salesforce\HttpClient\Authentication\AuthenticatedClientInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SalesForceClient implements SalesForceHttpClientInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly AuthenticatedClientInterface $salesForceClient,
        private readonly string $salesForceRestDomain,
    ) {
    }

    public function sendEvent(SalesForceApiEventDefinition $eventDefinition): void
    {
        $eventDefinitionSerialized = $this->serializer->serialize(
            $eventDefinition,
            JsonEncoder::FORMAT,
            [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );

        $this->salesForceClient->request(
            'POST',
            sprintf('%s%s', $this->salesForceRestDomain, '/interactions/v1/events'),
            [
                'body' => $eventDefinitionSerialized,
            ]);
    }
}
