<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintReportSend;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintReportSendHandler
{
    public function __invoke(ComplaintReportSendMessage $message): void
    {
        // TODO: Send salesforce email
    }
}
