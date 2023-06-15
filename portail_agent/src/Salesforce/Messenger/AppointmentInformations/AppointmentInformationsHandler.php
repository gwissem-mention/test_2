<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\AppointmentInformations;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AppointmentInformationsHandler
{
    public function __invoke(AppointmentInformationsMessage $message): void
    {
        // TODO: Send salesforce email
    }
}
