<?php

namespace Complaint\Parser;

use App\Complaint\Parser\AdditionalInformationParser;
use App\Entity\AdditionalInformation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdditionalInformationParserTest extends KernelTestCase
{
    public function getParser(): AdditionalInformationParser
    {
        self::bootKernel();
        $container = static::getContainer();
        /** @var AdditionalInformationParser $parser */
        $parser = $container->get(AdditionalInformationParser::class);

        return $parser;
    }

    public function testParse(): void
    {
        $parser = $this->getParser();

        $additionalInformationContent = json_decode('
{
	"suspectsChoice": true,
	"witnesses": true,
	"fsiVisit": true,
	"cctvPresent":
	{
		"code": 1,
		"label": "pel.yes"
	},
	"suspectsText": "Suspects text",
	"witnessesText": "Witnesses text",
	"observationMade": true,
	"cctvAvailable": true
}', false, 512, JSON_THROW_ON_ERROR);

        $additionalInformation = $parser->parse($additionalInformationContent);

        $this->assertInstanceOf(AdditionalInformation::class, $additionalInformation);
        $this->assertSame('Suspects text', $additionalInformation->getSuspectsKnownText());
        $this->assertSame('Witnesses text', $additionalInformation->getWitnessesPresentText());
        $this->assertSame(1, $additionalInformation->getCctvPresent());
        $this->assertTrue($additionalInformation->isCctvAvailable());
        $this->assertTrue($additionalInformation->isSuspectsKnown());
        $this->assertTrue($additionalInformation->isWitnessesPresent());
        $this->assertTrue($additionalInformation->isFsiVisit());
        $this->assertTrue($additionalInformation->isObservationMade());
    }

    public function testParseAllFalse(): void
    {
        $parser = $this->getParser();

        $additionalInformationContent = json_decode('
{
	"suspectsChoice": false,
	"witnesses": false,
	"fsiVisit": false,
	"cctvPresent":
	{
		"code": 2,
		"label": "pel.no"
	},
	"suspectsText": null,
	"witnessesText": null,
	"observationMade": false,
	"cctvAvailable": false
}', false, 512, JSON_THROW_ON_ERROR);

        $additionalInformation = $parser->parse($additionalInformationContent);

        $this->assertInstanceOf(AdditionalInformation::class, $additionalInformation);
        $this->assertNull($additionalInformation->getSuspectsKnownText());
        $this->assertNull($additionalInformation->getWitnessesPresentText());
        $this->assertSame(2, $additionalInformation->getCctvPresent());
        $this->assertFalse($additionalInformation->isCctvAvailable());
        $this->assertFalse($additionalInformation->isSuspectsKnown());
        $this->assertFalse($additionalInformation->isWitnessesPresent());
        $this->assertFalse($additionalInformation->isFsiVisit());
        $this->assertFalse($additionalInformation->isObservationMade());
    }
}
