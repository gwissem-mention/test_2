<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintRejection;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintRejectionHandler
{
    public function __invoke(ComplaintRejectionMessage $message): void
    {
        // TODO: Send salesforce email
    }
}
