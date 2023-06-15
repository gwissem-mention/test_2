<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintAssignment;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintAssignmentHandler
{
    public function __invoke(ComplaintAssignmentMessage $message): void
    {
        // TODO: Send salesforce email
    }
}
