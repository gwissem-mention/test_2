<?php

declare(strict_types=1);

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

        /** @var object{code: string, number: string} $phoneInputObject */
        $phoneInputObject = json_decode($phoneJson, false, 512, JSON_THROW_ON_ERROR);

        $phone = $parser->parse($phoneInputObject);

        $this->assertSame('+33 0601020304', $phone);
    }
}
