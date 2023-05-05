<?php

namespace Complaint\Parser;

use App\Complaint\Parser\PhoneParser;
use PHPUnit\Framework\TestCase;

class PhoneParserTest extends TestCase
{
    public function testParse(): void
    {
        $phoneJson = <<<JSON
{
"country": "FR",
"code": "33",
"number": "0601020304"
}
JSON;
        $parser = new PhoneParser();
        $phoneInputObject = json_decode($phoneJson);

        $phone = $parser->parse($phoneInputObject);

        $this->assertSame('+33 0601020304', $phone);
    }
}
