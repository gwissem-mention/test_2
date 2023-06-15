<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintFetch;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintFetchHandler
{
    public function __invoke(ComplaintFetchMessage $message): void
    {
        // TODO: Send salesforce email
    }
}
