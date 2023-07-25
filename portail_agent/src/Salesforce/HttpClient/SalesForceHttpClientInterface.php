<?php

namespace App\Salesforce\HttpClient;

interface SalesForceHttpClientInterface
{
    public function sendEvent(SalesForceApiEventDefinition $eventDefinition): void;
}
