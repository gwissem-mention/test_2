<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;

class HttpContext extends RawMinkContext
{
    /**
     * @Given the following HTTP headers
     */
    public function pushHttpHeaders(TableNode $tableNode): void
    {
        foreach ($tableNode->getHash() as $hash) {
            $this->getSession()->setRequestHeader($hash['name'], $hash['value']);
        }
    }
}
