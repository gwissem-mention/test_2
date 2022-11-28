<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\User;
use App\Repository\UserRepository;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BaseContext extends MinkContext
{
    private const RETRY_SLEEP = 100000;
    private const RETRY_MAX_TIME = 10;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UserRepository $userRepository,
        private readonly SessionFactoryInterface $sessionFactory,
        private readonly ContainerInterface $behatDriverContainer
    ) {
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
     * @Given /^I am connected as "([^"]+)" \((?P<id>[^\)]+)\)$/
     */
    public function iAmConnectedAs(string $id): void
    {
        $user = $this->userRepository->findOneByIdentifier($id);

        if (!$user instanceof User) {
            throw new \RuntimeException(sprintf('User %s not found', $id));
        }

        /** @var SymfonyDriver|Selenium2Driver $driver */
        $driver = $this->getSession()->getDriver();
        if ($driver instanceof Selenium2Driver) {
            $this->visitPath('/'); // Force session creation on client side
            $token = new TestBrowserToken($user->getRoles(), $user, 'main');
            $session = $this->sessionFactory->createSession();
            $session->set('_security_main', serialize($token));
            $session->save();
            $this->getSession()->setCookie($session->getName(), $session->getId());
        } else {
            $driver->getClient()->loginUser($user);
        }
    }

    /**
     * @Then I should not be connected
     */
    public function iShouldNotBeConnected(): void
    {
        $connectedUser = $this->behatDriverContainer->get('security.helper')->getUser();
        if ($connectedUser instanceof UserInterface) {
            throw new ExpectationException(sprintf('User connected as %s', $connectedUser->getUserIdentifier()), $this->getSession()->getDriver());
        }
    }

    /**
     * @Then I should be connected as :username
     */
    public function iShouldBeConnectedAs(string $username): void
    {
        $connectedUser = $this->behatDriverContainer->get('security.helper')->getUser();

        if (!$connectedUser instanceof User) {
            throw new ExpectationException('User is not connected', $this->getSession()->getDriver());
        }

        if ($username !== $connectedUser->getFullName()) {
            throw new ExpectationException(sprintf('Wrong user connected (expected: %s, actual: %s)', $username, $connectedUser->getFullName()), $this->getSession()->getDriver());
        }
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

    private function retryStep(
        callable $step,
        int $maxTime = self::RETRY_MAX_TIME,
        int $sleep = self::RETRY_SLEEP
    ): void {
        $startTime = \time();
        do {
            try {
                $step();

                return;
            } catch (ExpectationException $e) {
                $ex = $e;
            }
            \usleep($sleep);
        } while (\time() - $startTime <= $maxTime);

        throw $ex;
    }
}
