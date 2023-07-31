<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient;

interface SalesForceHttpClientInterface
{
    public function sendEvent(SalesForceApiEventDefinition $eventDefinition): void;
}
