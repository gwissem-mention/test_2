<?php

declare(strict_types=1);

namespace App\Tests\Complaint\Parser;

use App\Complaint\Parser\DateParser;
use PHPUnit\Framework\TestCase;

class DateParserTest extends TestCase
{
    public function testParseImmutable(): void
    {
        $dateJson = <<<JSON
{
    "date": "1962-08-24T00:00:00+00:00",
    "timestamp": -232156800,
    "timezone": "+00:00"
}
JSON;
        $parser = new DateParser();
        $dateInputObject = json_decode($dateJson);

        $date = $parser->parseImmutable($dateInputObject);

        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
        $this->assertSame('1962-08-24T00:00:00+00:00', $date->format('c'));
        $this->assertSame(-232156800, $date->getTimestamp());
        $this->assertSame('+00:00', $date->getTimezone()->getName());
    }

    public function testParse(): void
    {
        $dateJson = <<<JSON
{
    "date": "1970-01-01T18:33:00+00:00",
    "timestamp": 66780,
    "timezone": "+00:00"
}
JSON;
        $parser = new DateParser();
        $dateInputObject = json_decode($dateJson);

        $date = $parser->parseImmutable($dateInputObject);

        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
        $this->assertSame('1970-01-01T18:33:00+00:00', $date->format('c'));
        $this->assertSame(66780, $date->getTimestamp());
        $this->assertSame('+00:00', $date->getTimezone()->getName());
    }
}
