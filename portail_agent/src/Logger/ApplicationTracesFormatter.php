<?php

declare(strict_types=1);

namespace App\Logger;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class ApplicationTracesFormatter implements FormatterInterface
{
    public function format(LogRecord $record)
    {
        return $record['message'];
    }

    /**
     * @return array<int, LogRecord>
     */
    public function formatBatch(array $records): array
    {
        foreach ($records as $key => $record) {
            $records[$key] = $this->format($record);
        }

        return $records;
    }
}
