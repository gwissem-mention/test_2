<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Session;

class GoogleMapsContext implements Context
{
    private const RETRY_SLEEP = 100000;
    private const RETRY_MAX_TIME = 10;

    public function __construct(private readonly Session $session)
    {
    }

    /**
     * @When /^I click on the map at latitude "([^"]*)" and longitude "([^"]*)"$/
     */
    public function iClickOnTheMapAtLatitudeAndLongitude(string $latitude, string $longitude): void
    {
        $latLng = "{$latitude},{$longitude}";

        $this->session->wait(5000, 'typeof map !== "undefined" && map !== null && typeof map.setCenter === "function"');
        $this->session->executeScript("map.setCenter(new google.maps.LatLng($latLng));");
        $this->session->executeScript("google.maps.event.trigger(map, 'click', {latLng: new google.maps.LatLng($latLng)});");
    }

    /**
     * @Then /^the marker should be at latitude "([^"]*)" and longitude "([^"]*)"$/
     */
    public function theMarkerShouldBeAtLatitudeAndLongitude(string $latitude, string $longitude): void
    {
        $this->session->wait(5000, 'typeof marker !== "undefined" && marker !== null');
        $lat = (string) $this->session->evaluateScript('marker.getPosition().lat()');
        $lng = (string) $this->session->evaluateScript('marker.getPosition().lng()');

        if ($lat === $latitude && $lng === $longitude) {
            return;
        }

        throw new ExpectationException('Marker not found', $this->session->getDriver());
    }

    /**
     * @When /^I fill in the map autocomplete "([^"]*)" with "([^"]*)" and click on the first result$/
     *
     * @throws ElementNotFoundException
     */
    public function fillInMapAutocomplete(string $field, string $query): void
    {
        $this->retryStep(function () use ($field, $query) {
            $element = $this->session->getPage()->findField($field);
            if (null === $element) {
                throw new ElementNotFoundException($this->session, null, 'named', $field);
            }

            $element->setValue($query);
            $element->focus();

            $xpath = $element->getXpath();
            $driver = $this->session->getDriver();

            $driver->keyDown($xpath, 40);
            $driver->keyUp($xpath, 40);

            $this->wait(500);

            $autocompletes = $this->session->getPage()->findAll('css', '.pac-container');
            if (empty($autocompletes)) {
                throw new \RuntimeException('Could not find the autocomplete popup box');
            }
            $matchedElement = null;

            foreach ($autocompletes as $autocomplete) {
                if ($autocomplete->isVisible()) {
                    $selector = '//div[contains(@class, "pac-item")]';
                    $matchedElement = $autocomplete->find('xpath', $selector);
                    if (!empty($matchedElement)) {
                        $matchedElement->click();
                        $this->wait(500);
                    }
                }
            }

            if (null === $matchedElement) {
                throw new ExpectationException('Match element not found', $this->session->getDriver());
            }
        });
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

    private function wait(int $time): void
    {
        $this->session->wait($time);
    }
}
