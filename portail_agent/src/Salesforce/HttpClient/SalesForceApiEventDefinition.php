<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient;

use App\Salesforce\HttpClient\ApiDataFormat\SalesForceApiDataInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

class SalesForceApiEventDefinition
{
    public function __construct(
        #[SerializedName('EventDefinitionKey')] public string $eventDefinitionKey,
        #[SerializedName('ContactKey')] public string $contactKey,
        #[SerializedName('Data')] public SalesForceApiDataInterface $data
    ) {
    }
}
