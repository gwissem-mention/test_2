<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Session;

class GoogleMapsContext implements Context
{
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

        $markerIsAtPosition = (bool) $this->session->evaluateScript("marker.getPosition().lat() === $latitude && marker.getPosition().lng() === $longitude");

        if (true === $markerIsAtPosition) {
            return;
        }

        throw new ExpectationException('Marker not found', $this->session->getDriver());
    }
}
