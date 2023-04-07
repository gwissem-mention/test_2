<?php

declare(strict_types=1);

namespace App\Report;

use App\Oodrive\ApiClientInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendReportHandler
{
    public function __construct(
        private readonly ApiClientInterface $oodriveClient,
        private readonly string $tmpComplaintFolderId
    ) {
    }

    public function __invoke(SendReport $sendReport): void
    {
        // TODO: make this dynamic based on the complaint, when we know the complaint id
        $this->oodriveClient->uploadFile($sendReport->getReport(), 'PV.pdf', $this->tmpComplaintFolderId);
    }
}
