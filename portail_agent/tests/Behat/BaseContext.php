<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\MinkExtension\Context\MinkContext;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BaseContext extends MinkContext
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * @Then I should see the key :arg1 translated
     */
    public function iShouldSeeTheKeyTranslated(string $arg1): void
    {
        parent::assertPageNotContainsText($arg1);
        parent::assertResponseNotContains($arg1);
        parent::assertPageContainsText($this->translator->trans($arg1));
    }

    /**
     * @Then I should not see the key :arg1 translated
     */
    public function iShouldNotSeeTheKeyTranslated(string $arg1): void
    {
        parent::assertPageNotContainsText($arg1);
        parent::assertResponseNotContains($arg1);
        parent::assertPageNotContainsText($this->translator->trans($arg1));
    }

    /**
     * @Then the response should contain the key :arg1 translated
     */
    public function assertResponseContainsTheKeyTranslated(string $arg1): void
    {
        parent::assertSession()->responseNotContains($arg1);
        parent::assertSession()->responseContains(htmlspecialchars($this->translator->trans($arg1)));
    }
}
