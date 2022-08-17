<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\MinkExtension\Context\MinkContext;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BaseContext extends MinkContext
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @Then I should see the key :arg1 translated
     */
    public function iShouldSeeTheKeyTranslated(string $arg1): void
    {
        $this->assertPageContainsText($this->translator->trans($arg1));
    }
}
