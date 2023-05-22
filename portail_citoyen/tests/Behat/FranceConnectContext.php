<?php

namespace App\Tests\Behat;

use App\Security\FranceConnectListener;
use App\Tests\Doubles\FranceConnectGuzzleClientFake;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Session;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FranceConnectContext implements Context
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Session $session,
        private readonly string $baseUri,
    ) {
    }

    /**
     * @Given An error will occur during France Connect access token retrieval
     */
    public function AnErrorWillOccurDuringFranceConnectAccessTokenRetrieval(): void
    {
        FranceConnectGuzzleClientFake::$accessTokenServerError = true;
    }

    /**
     * @Given An error will occur during France Connect user info retrieval
     */
    public function AnErrorWillOccurDuringFranceConnectUserInfoRetrieval(): void
    {
        FranceConnectGuzzleClientFake::$userInfoServerError = true;
    }

    /**
     * @Then I should be redirected to the France Connect logout page
     */
    public function iShouldBeRedirectedToTheFranceConnectLogoutPage(): void
    {
        $this->assert(302, $this->session->getStatusCode(), 'Wrong status code');

        $actualLocation = $this->session->getResponseHeader('Location') ?? '';

        if (!str_contains($actualLocation, '?')) {
            $actualLocation .= '?';
        }

        [$url, $qs] = explode('?', $actualLocation);
        $this->assert(rtrim($this->baseUri, '/').'/logout', $url, sprintf('Wrong url'));

        parse_str($qs, $result);

        /* @phpstan-ignore-next-line */
        $this->assert('my-id-token', strval($result['id_token_hint']), 'Wrong id_token_hint query param');
        $this->assert(
            $this->urlGenerator->generate(FranceConnectListener::LOGOUT_CALLBACK_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL),
            /* @phpstan-ignore-next-line */
            strval($result['post_logout_redirect_uri']), 'Wrong post_logout_redirect_uri query param',
        );

        if (empty($result['state'])) {
            throw new ExpectationException('State query param missing or empty in logout call', $this->session);
        }
    }

    /**
     * @Then the FranceConnect button is displayed
     */
    public function theFranceConnectButtonIsDisplayed(): void
    {
        $driver = $this->session->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            throw new \BadMethodCallException('Only symfony driver is supported for this step');
        }

        if (0 === $driver->getClient()->getCrawler()->filter('#france_connect_auth_button')->count()) {
            throw new ExpectationException('France Connect button not found in the page', $this->session);
        }
    }

    /**
     * @Then the FranceConnect button is not displayed
     */
    public function theFranceConnectButtonIsNotDisplayed(): void
    {
        $driver = $this->session->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            throw new \BadMethodCallException('Only symfony driver is supported for this step');
        }

        if (0 < $driver->getClient()->getCrawler()->filter('#france_connect_auth_button')->count()) {
            throw new ExpectationException('France Connect button found in the page', $this->session);
        }
    }

    private function assert(string|int $expected, string|int $actual, string $message): void
    {
        if ($actual !== $expected) {
            throw new ExpectationException(sprintf('%s (expected %s actual %s)', $message, $expected, $actual), $this->session);
        }
    }
}
