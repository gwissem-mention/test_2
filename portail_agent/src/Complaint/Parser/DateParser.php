<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

class DateParser
{
    public function parseImmutable(object $dateInput): \DateTimeImmutable
    {
        $dateTimeZone = new \DateTimeZone($dateInput->timezone);

        /** @var \DateTimeImmutable $dateTime */
        $dateTime = \DateTimeImmutable::createFromFormat(\DateTime::ATOM, $dateInput->date, $dateTimeZone);

        return $dateTime;
    }

    public function parse(object $dateInput): \DateTime
    {
        $dateTimeZone = new \DateTimeZone($dateInput->timezone);

        /** @var \DateTime $dateTime */
        $dateTime = \DateTime::createFromFormat(\DateTime::ATOM, $dateInput->date, $dateTimeZone);

        return $dateTime;
    }
}
