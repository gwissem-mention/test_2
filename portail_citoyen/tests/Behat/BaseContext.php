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
        $this->assertPageNotContainsText($arg1);
        $this->assertResponseNotContains($arg1);
        $this->assertPageContainsText($this->translator->trans($arg1));
    }

    /**
     * @Then I should not see the key :arg1 translated
     */
    public function iShouldNotSeeTheKeyTranslated(string $arg1): void
    {
        $this->assertPageNotContainsText($arg1);
        $this->assertResponseNotContains($arg1);
        $this->assertPageNotContainsText($this->translator->trans($arg1));
    }

    /**
     * @Then I should see the key :arg1 translated in the response
     */
    public function iShouldSeeTheKeyTranslatedInTheResponse(string $arg1): void
    {
        $this->assertPageNotContainsText($arg1);
        $this->assertResponseNotContains($arg1);
        $this->assertResponseContains($this->translator->trans($arg1));
    }

    /**
     * @Then /^I wait for the element "([^"]*)" to appear$/
     */
    public function iWaitForTheElementToAppear(string $selector): void
    {
        $this->getSession()->wait(
            500,
            "document.querySelector('".$selector."') !== null"
        );
    }

    /**
     * @Then /^I wait for the element "([^"]*)" to disappear$/
     */
    public function iWaitForTheElementToDisappear(string $selector): void
    {
        $this->getSession()->wait(
            500,
            "document.querySelector('".$selector."') === null"
        );
    }

    /**
     * @Then /^I wait for the element "([^"]*)" to be filled$/
     */
    public function iWaitForTheElementToBeFilled(string $selector): void
    {
        $this->getSession()->wait(
            500,
            "document.querySelector('".$selector."').childNodes.length > 0"
        );
    }

    /**
     * @When /^I click the "([^"]*)" element/
     */
    public function iClick(string $selector): void
    {
        $page = $this->getSession()->getPage();
        $this->iWaitForTheElementToAppear($selector);
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
            500,
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
            500,
            "document.querySelector('".$arg1."').value === ".$arg2
        );

        $this->assertFieldContains(str_replace('#', '', $arg1), $arg2);
    }

    /**
     * @When I wait for the element :arg1 to be enabled
     */
    public function iWaitForTheElementToBeEnabled(string $arg1): void
    {
        $this->getSession()->wait(
            500,
            "document.querySelector('".$arg1."').disabled === false"
        );
    }

    /**
     * @When /^I attach the file "(?P<path>[^"]*)" to "(?P<field>(?:[^"]|\\")*)" field/
     */
    public function iAttachFileToField(string $selector, string $path): void
    {
        $this->iWaitForTheElementToAppear($selector);
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

    /**
     * @When /^I wait (?P<num>\d+) ms$/
     */
    public function iWait(int $time): void
    {
        $this->getSession()->wait(
            $time,
        );
    }

    /**
     * @Then I am redirected on :url
     */
    public function iAmRedirectedOn(string $url): void
    {
        $this->visitPath($url);
    }

    /**
     * Fills in form field with specified id|name|label|value
     * Example: When I wait and fill in "username" with: "bwayne"
     * Example: And I wait and fill in "bwayne" for "username".
     *
     * @When /^(?:|I )wait and fill in "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
     * @When /^(?:|I )wait and fill in "(?P<field>(?:[^"]|\\")*)" with:$/
     * @When /^(?:|I )wait and fill in "(?P<value>(?:[^"]|\\")*)" for "(?P<field>(?:[^"]|\\")*)"$/
     */
    public function waitAndFillField(string $field, string $value): void
    {
        $this->iWaitForTheElementToAppear($field);
        $this->fillField($field, $value);
    }

    /**
     * Selects option in select field with specified id|name|label|value
     * Example: When I wait and select "Bats" from "user_fears"
     * Example: And I wait and select "Bats" from "user_fears".
     *
     * @When /^(?:|I )wait and select "(?P<option>(?:[^"]|\\")*)" from "(?P<select>(?:[^"]|\\")*)"$/
     */
    public function waitAndSelectOption(string $select, string $option): void
    {
        $this->iWaitForTheElementToAppear($select);
        $this->selectOption($select, $option);
    }
}
