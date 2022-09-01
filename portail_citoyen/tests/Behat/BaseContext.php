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

    /**
     * @Then /^I wait for the element "([^"]*)" to appear$/
     */
    public function iWaitForTheElementToAppear(string $selector): void
    {
        $this->getSession()->wait(
            5000,
            "document.querySelector('".$selector."')"
        );
    }

    /**
     * @Then /^I wait for the element "([^"]*)" to be filled$/
     */
    public function iWaitForTheElementToBeFilled(string $selector): void
    {
        $this->getSession()->wait(
            5000,
            "document.querySelector('".$selector."').childNodes.length > 0"
        );
    }

    /**
     * @When /^I click the "([^"]*)" element/
     */
    public function iClick(string $selector): void
    {
        $page = $this->getSession()->getPage();
        $element = $page->find('css', $selector);

        if (is_null($element)) {
            throw new \RuntimeException('Element not found');
        }
        $element->click();
    }

    /**
     * @When I wait for the element :arg1 to contain :arg2
     */
    public function iWaitForTheElementToContain(string $arg1, string $arg2): void
    {
        $this->getSession()->wait(
            5000,
            "document.querySelector('".$arg1."').innerHTML ===".$arg2
        );

        $this->assertElementContainsText($arg1, $arg2);
    }

    /**
     * @When /^I wait for the "(?P<field>(?:[^"]|\\")*)" field to contain "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function iWaitForTheFieldToContainValue(string $arg1, string $arg2): void
    {
        $this->getSession()->wait(
            5000,
            "document.querySelector('".$arg1."').value === ".$arg2
        );

        $this->assertFieldContains(str_replace('#', '', $arg1), $arg2);
    }

    /**
     * @When /^I attach the file "(?P<path>[^"]*)" to "(?P<field>(?:[^"]|\\")*)" field/
     */
    public function iAttachFileToField(string $selector, string $path): void
    {
        $field = $this->getSession()->getPage()->find('css', $selector);

        if ($this->getMinkParameter('files_path')) {
            $fullPath = rtrim(
                strval(realpath(strval($this->getMinkParameter('files_path')))),
                DIRECTORY_SEPARATOR
            ).DIRECTORY_SEPARATOR.$path;
            if (is_file($fullPath)) {
                $path = $fullPath;
            }
        }

        $field?->attachFile($path);
    }
}
