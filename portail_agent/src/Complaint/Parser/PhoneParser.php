<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

class PhoneParser
{
    public function parse(object $phone): string
    {
        return '+'.$phone->code.' '.$phone->number;
    }
}
