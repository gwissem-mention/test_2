<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

/**
 * @phpstan-type JsonPhone object{code: string, number: string}
 */
class PhoneParser
{
    /**
     * @param JsonPhone $phone
     */
    public function parse(object $phone): string
    {
        return '+'.$phone->code.' '.$phone->number;
    }
}
