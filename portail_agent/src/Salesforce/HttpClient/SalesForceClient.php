<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient;

use App\Salesforce\HttpClient\Authentication\AuthenticatedClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class SalesForceClient implements SalesForceHttpClientInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly AuthenticatedClientInterface $salesForceClient,
        private readonly string $salesForceRestDomain,
        private readonly LoggerInterface $salesforceLogger
    ) {
    }

    public function sendEvent(SalesForceApiEventDefinition $eventDefinition): void
    {
        try {
            $eventDefinitionSerialized = $this->serializer->serialize(
                $eventDefinition,
                JsonEncoder::FORMAT,
                [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
            );

            $requestUrl = sprintf('%s%s', $this->salesForceRestDomain, '/interaction/v1/events');
            $this->salesforceLogger->info(
                sprintf('Start sendEvent request, method %s, url %s, body %s',
                    'POST',
                    $requestUrl,
                    $eventDefinitionSerialized
                ));

            $response = $this->salesForceClient->request(
                'POST',
                $requestUrl,
                [
                    'body' => $eventDefinitionSerialized,
                ]);

            if (Response::HTTP_CREATED !== $response->getStatusCode()) {
                $this->salesforceLogger->error(sprintf('Failing sending event, status code %s, response content %s', $response->getStatusCode(), $response->getContent(false)));
            }

            $this->salesforceLogger->info(sprintf('Successfully event sent, response content %s', $response->getContent(false)));
        } catch (ExceptionInterface $exception) {
            $this->salesforceLogger->error(sprintf('Failing sending event, exception %s', $exception->getMessage()));
        }
    }
}
