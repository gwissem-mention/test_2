<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\Appointment;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AppointmentHandler
{
    public function __invoke(AppointmentMessage $message): void
    {
        // TODO: Send salesforce email
    }
}
