<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

/**
 * @phpstan-type JsonDate object{date: string, timezone: string}
 */
class DateParser
{
    /**
     * @param JsonDate $dateInput
     *
     * @throws \Exception
     */
    public function parseImmutable(object $dateInput): \DateTimeImmutable
    {
        $dateTimeZone = new \DateTimeZone($dateInput->timezone);

        /** @var \DateTimeImmutable $dateTime */
        $dateTime = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $dateInput->date, $dateTimeZone);

        return $dateTime;
    }

    /**
     * @param JsonDate $dateInput
     *
     * @throws \Exception
     */
    public function parse(object $dateInput): \DateTime
    {
        $dateTimeZone = new \DateTimeZone($dateInput->timezone);

        /** @var \DateTime $dateTime */
        $dateTime = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $dateInput->date, $dateTimeZone);

        return $dateTime;
    }
}
