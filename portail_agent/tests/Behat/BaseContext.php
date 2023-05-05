<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Generator\ComplaintNumber\ComplaintNumberGeneratorInterface;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BaseContext extends MinkContext
{
    private const RETRY_SLEEP = 100000;
    private const RETRY_MAX_TIME = 10;
    private readonly UserContext $userContext;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ComplaintNumberGeneratorInterface $complaintNumberGenerator
    ) {
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $this->userContext = $scope->getEnvironment()->getContext(UserContext::class);
    }

    /**
     * @AfterScenario
     */
    public function resetHeaders(AfterScenarioScope $afterScenario): void
    {
        $session = $this->getSession();
        $driver = $session->getDriver();

        if (!$driver instanceof SymfonyDriver) {
            return;
        }

        // Reset severs parameters after each scenario, because it's not done by the client during the restart process
        $driver->getClient()->setServerParameters([]);
    }

    /** @AfterScenario */
    public function resetSession(AfterScenarioScope $scope): void
    {
        if (!$this->getSession()->getDriver() instanceof Selenium2Driver) {
            return;
        }

        $this->getSession()->reset();
    }

    public function visit($page): void
    {
        $this->setHeaders(function () use ($page) {
            parent::visit($page);
        });
    }

    public function iAmOnHomepage(): void
    {
        $this->setHeaders(function () {
            parent::iAmOnHomepage();
        });
    }

    /**
     * @Then I should see the key :arg1 translated
     */
    public function iShouldSeeTheKeyTranslated(string $arg1): void
    {
        $this->retryStep(function () use ($arg1) {
            parent::assertPageNotContainsText($arg1);
            parent::assertResponseNotContains($arg1);
            parent::assertPageContainsText($this->translator->trans($arg1));
        });
    }

    /**
     * @Then I should not see the key :arg1 translated
     */
    public function iShouldNotSeeTheKeyTranslated(string $arg1): void
    {
        $this->retryStep(function () use ($arg1) {
            parent::assertPageNotContainsText($arg1);
            parent::assertResponseNotContains($arg1);
            parent::assertPageNotContainsText($this->translator->trans($arg1));
        });
    }

    /**
     * @Then I should see the key :arg1 translated in the response
     */
    public function iShouldSeeTheKeyTranslatedInTheResponse(string $arg1): void
    {
        $this->retryStep(function () use ($arg1) {
            parent::assertPageNotContainsText($arg1);
            parent::assertResponseNotContains($arg1);
            parent::assertResponseContains($this->translator->trans($arg1));
        });
    }

    /**
     * @When /^I click the "([^"]*)" element$/
     */
    public function iClick(string $selector): void
    {
        $this->retryStep(function () use ($selector) {
            $page = $this->getSession()->getPage();
            $element = $page->find('css', $selector);

            if (null === $element) {
                throw new ExpectationException('element empty', $this->getSession()->getDriver());
            }

            $element->click();
        });
    }

    public function fillField(mixed $field, mixed $value): void
    {
        $this->retryStep(function () use ($field, $value) {
            parent::fillField($field, $value);
            $element = $this->getSession()->getPage()->find('css', '#'.$field)?->getValue();

            if ('' === $element) {
                throw new ExpectationException('element empty', $this->getSession()->getDriver());
            }
        });
    }

    public function selectOption(mixed $select, mixed $option): void
    {
        $this->retryStep(function () use ($select, $option) {
            parent::selectOption($select, $option);
        });
    }

    public function assertElementOnPage(mixed $element): void
    {
        $this->retryStep(function () use ($element) {
            parent::assertElementOnPage($element);
        });
    }

    public function assertElementNotOnPage(mixed $element): void
    {
        $this->retryStep(function () use ($element) {
            parent::assertElementNotOnPage($element);
        });
    }

    public function pressButton(mixed $button): void
    {
        $this->retryStep(function () use ($button) {
            parent::pressButton($button);
        });
    }

    public function clickLink(mixed $link): void
    {
        $this->retryStep(function () use ($link) {
            parent::clickLink($link);
        });
    }

    /**
     * @Given I go to the tab :tab
     */
    public function goToTab(int $tab): void
    {
        $windowNames = $this->getSession()->getWindowNames();
        $this->getSession()->switchToWindow($windowNames[$tab - 1]);
    }

    public function assertElementContainsText(mixed $element, mixed $text): void
    {
        $this->retryStep(function () use ($element, $text) {
            parent::assertElementContainsText($element, $text);
        });
    }

    public function assertPageContainsText(mixed $text): void
    {
        $this->retryStep(function () use ($text) {
            parent::assertPageContainsText($text);
        });
    }

    public function assertFieldContains(mixed $field, mixed $value): void
    {
        $this->retryStep(function () use ($field, $value) {
            parent::assertFieldContains($field, $value);
        });
    }

    public function assertFieldNotContains(mixed $field, mixed $value): void
    {
        $this->retryStep(function () use ($field, $value) {
            parent::assertFieldNotContains($field, $value);
        });
    }

    public function assertNumElements(mixed $num, mixed $element): void
    {
        $this->retryStep(function () use ($num, $element) {
            parent::assertNumElements($num, $element);
        });
    }

    public function assertElementContains(mixed $element, mixed $value): void
    {
        $this->retryStep(function () use ($element, $value) {
            parent::assertElementContains($element, $value);
        });
    }

    public function assertElementNotContains(mixed $element, mixed $value): void
    {
        $this->retryStep(function () use ($element, $value) {
            parent::assertElementNotContains($element, $value);
        });
    }

    /**
     * @Then I am redirected on :url
     */
    public function iAmRedirectedOn(string $url): void
    {
        $this->visitPath($url);
    }

    /**
     * @Given follow redirects is disabled
     */
    public function followRedirectsIsDisabled(): void
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            throw new \BadMethodCallException(sprintf('Follow redirects can only be disabled with Symfony driver (%s used)', get_class($driver)));
        }

        $driver->getClient()->followRedirects(false);
    }

    /**
     * @BeforeScenario
     */
    public function enableFollowRedirects(): void
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            return;
        }

        $driver->getClient()->followRedirects();
    }

    /**
     * @Then I follow the redirection
     */
    public function iFollowTheRedirection(): void
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            throw new \BadMethodCallException(sprintf('Follow the redirection can only be used with Symfony driver (%s used)', get_class($driver)));
        }

        if (302 !== $this->getSession()->getStatusCode()) {
            throw new ExpectationException('Response is not a redirection', $driver);
        }

        $location = $this->getSession()->getResponseHeader('Location');
        if (null === $location) {
            throw new ExpectationException('Location header not present in response', $driver);
        }

        $driver->getClient()->followRedirect();
    }

    /**
     * @Then the response should contain the key :arg1 translated
     */
    public function assertResponseContainsTheKeyTranslated(string $arg1): void
    {
        parent::assertSession()->responseNotContains($arg1);
        parent::assertSession()->responseContains(htmlspecialchars($this->translator->trans($arg1)));
    }

    /**
     * @When I fill in the autocomplete :autocomplete with :query and click :foundValue
     *
     * @throws ElementNotFoundException
     */
    public function fillInAutocomplete(string $field, string $query, string $foundValue): void
    {
        $this->retryStep(function () use ($field, $query, $foundValue) {
            $session = $this->getSession();
            $element = $session->getPage()->findField($field);
            if (null === $element) {
                throw new ElementNotFoundException($session, null, 'named', $field);
            }

            $element->setValue($query);
            $element->focus();

            $xpath = $element->getXpath();
            $driver = $session->getDriver();

            $driver->keyDown($xpath, 40);
            $driver->keyUp($xpath, 40);

            $this->iWait(500);

            $autocompletes = $this->getSession()->getPage()->findAll('css', '.ts-dropdown-content');
            if (empty($autocompletes)) {
                throw new \RuntimeException('Could not find the autocomplete popup box');
            }

            foreach ($autocompletes as $autocomplete) {
                if ($autocomplete->isVisible()) {
                    $matchedElement = $autocomplete->find('xpath', "//div[@data-value='{$foundValue}']");
                    if (!empty($matchedElement)) {
                        $matchedElement->click();
                        $this->iWait(500);
                    }
                }
            }
        });
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
     * @Then I should focus the :arg1 element
     */
    public function IShouldFocus(string $arg1): void
    {
        assert((bool) $this->getSession()->evaluateScript(
            '(document.getElementById("'.$arg1.'") === document.activeElement)'
        ));
    }

    /**
     * @Then I should not focus the :arg1 element
     */
    public function IShouldNotFocus(string $arg1): void
    {
        assert(!$this->getSession()->evaluateScript(
            '(document.getElementById("'.$arg1.'") === document.activeElement)'
        ));
    }

    /**
     * @When /^I follow the declaration number (?P<num>\d+)$/
     */
    public function IFollowTheDeclarationNumber(int $declarationNumber): void
    {
        $this->retryStep(function () use ($declarationNumber) {
            $this->clickLink($this->complaintNumberGenerator->generate($declarationNumber));
        });
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

    private function retryStep(
        callable $step,
        int $maxTime = self::RETRY_MAX_TIME,
        int $sleep = self::RETRY_SLEEP
    ): void {
        $startTime = \time();
        do {
            try {
                $step();
                // we sleep a little bit more even when the element is found to wait for animation ending
                \usleep($sleep / 2);

                return;
            } catch (\Exception $e) {
                $ex = $e;
            }
            \usleep($sleep);
        } while (\time() - $startTime <= $maxTime);

        throw $ex;
    }

    private function setHeaders(callable $callback): void
    {
        $headers = $this->userContext->getHeaders();

        $session = $this->getSession();
        $driver = $session->getDriver();

        if ($driver instanceof SymfonyDriver && count($headers) > 0) {
            $client = $driver->getClient();

            /**
             * @var string $name
             * @var string $value
             */
            foreach ($headers as $name => $value) {
                $client->setServerParameter('HTTP_'.strtoupper($name), $value);
            }
        }

        $callback();
    }

    /**
     * @Given /^I should see at least (\d+) "([^"]*)" elements$/
     */
    public function iShouldSeeAtLeastElements(int $elementMinCount, string $elementSelector): void
    {
        $this->retryStep(function () use ($elementSelector, $elementMinCount) {
            $page = $this->getSession()->getPage();
            $element = $page->findAll('css', $elementSelector);

            if (count($element) < $elementMinCount) {
                throw new ExpectationException('Minimum element expected not found', $this->getSession()->getDriver());
            }
        });
    }

    /**
     * @Given /^I should see all "([^"]*)" checked$/
     */
    public function iShouldSeeAllCheckboxesCheckedInTheTable(string $selector): void
    {
        $this->retryStep(function () use ($selector) {
            $page = $this->getSession()->getPage();

            /** @var NodeElement[] $element */
            $element = $page->findAll('css', $selector);

            foreach ($element as $el) {
                if (false === $el->isChecked()) {
                    throw new ExpectationException('All checkbox are not checked', $this->getSession()->getDriver());
                }
            }
        });
    }
}
