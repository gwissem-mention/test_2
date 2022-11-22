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
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UserRepository $userRepository,
        private readonly SessionFactoryInterface $sessionFactory,
        private ContainerInterface $behatDriverContainer,
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
            throw new ExpectationException(sprintf('User connected as %s', $connectedUser->getUserIdentifier()), $this->session->getDriver());
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
            throw new ExpectationException(sprintf('Wrong user connected (expected: %s, actual: %s)', $username, $connectedUser->getFullName()), $this->session->getDriver());
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

        $driver->getClient()->followRedirects(true);
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
}
