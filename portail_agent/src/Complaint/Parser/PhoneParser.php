<?php

namespace App\Complaint\Parser;

class PhoneParser
{
    public function parse(object $phone): string
    {
        return '+'.$phone->code.' '.$phone->number;
    }
}
