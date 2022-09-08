Feature:
    In order to show place nature page
    As a user
    I need to see a select box with nature places

    @func
    Scenario Outline: Show place nature page and select a nature place
        Given I am on "/nature-de-lieu"
        Then the response status code should be 200
        And I should see the key "ministry" translated
        And I should see the key "inside" translated
        And I should see the key "and.overseas" translated
        And I should see 1 "body" element
        When I select "<nature_place>" from "place_nature_place"
        And I should see "<nature_place>" in the ".fr-select" element

        Examples:
            | nature_place           |
            | Domicile/Logement      |
            | Parking / garage       |
            | Voie publique / Rue    |
            | Commerce               |
            | Transports en commun   |
            | Autres natures de lieu |

    @javascript
    Scenario Outline: Show place nature page and select "Transports en commun" nature place
        Given I am on "/nature-de-lieu"
        And I should see 1 "body" element
        When I select "Transports en commun" from "place_nature_place"
        And I should see "Transports en commun" in the "#place_nature_place" element
        And I wait for the element "#place_nature_naturePlacePublicTransportChoice" to appear
        And I wait for the element "#place_nature_naturePlacePublicTransportChoice" to be enabled
        And I should see 2 ".fr-select" elements
        Then I select "<nature_place_public_transport>" from "place_nature_naturePlacePublicTransportChoice"
        And I should see "<nature_place_public_transport>" in the "#place_nature_naturePlacePublicTransportChoice" element

        Examples:
            | nature_place_public_transport |
            | Avion/Aéroport                |
            | Métro                         |
            | Bus                           |
            | Train/gare                    |
            | Tramway                       |
            | Bateau                        |
            | Autocar                       |
