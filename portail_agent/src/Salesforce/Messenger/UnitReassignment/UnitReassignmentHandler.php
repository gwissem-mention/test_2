<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\UnitReassignment;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UnitReassignmentHandler
{
    public function __invoke(UnitReassignmentMessage $message): void
    {
        // TODO: Send salesforce email
    }
}
