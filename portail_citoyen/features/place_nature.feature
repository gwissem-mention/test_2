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
        And I should see the key "file.a.complaint" translated
        And I should see the key "faq" translated
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
            | Lieu indéterminé       |

    @javascript
    Scenario Outline: Show place nature page and select "Transports en commun" nature place
        Given I am on "/nature-de-lieu"
        When I select "Transports en commun" from "place_nature_place"
        And I wait for the element "#place_nature_naturePlacePublicTransportChoice" to appear
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

    @javascript
    Scenario Outline: Show place nature page and select "Autres natures de lieu" nature place
        Given I am on "/nature-de-lieu"
        When I select "Autres natures de lieu" from "place_nature_place"
        And I wait for the element "#place_nature_naturePlaceOtherChoice" to appear
        Then I select "<nature_place_other>" from "place_nature_naturePlaceOtherChoice"
        And I should see "<nature_place_other>" in the "#place_nature_naturePlaceOtherChoice" element

        Examples:
            | nature_place_other |
            | Camping            |
            | Restaurant         |
            | Parc               |
            | Marché             |

    @javascript
    Scenario: Show place nature page and choose hour facts
        Given I am on "/nature-de-lieu"
        And I should see 1 "body" element
        When I click the "label[for='place_nature_choiceHour_0']" element
        Then I wait for the element "#place_nature_hour" to appear
        And I should not see a "#place_nature_startHour" element
        And I should not see a "#place_nature_endHour" element

    @javascript
    Scenario: Show place nature page and choose hour facts
        Given I am on "/nature-de-lieu"
        And I should see 1 "body" element
        When I click the "label[for='place_nature_choiceHour_1']" element
        Then I wait for the element "#place_nature_startHour" to appear
        And I wait for the element "#place_nature_endHour" to appear
        And I should not see a "#place_nature_hour" element

    @javascript
    Scenario: Show place nature page and choose hour facts
        Given I am on "/nature-de-lieu"
        And I should see 1 "body" element
        When I click the "label[for='place_nature_choiceHour_2']" element
        Then I should not see a "#place_nature_hour" element
        And I should not see a "#place_nature_startHour" element
        And I should not see a "#place_nature_endHour" element

    @func
    Scenario: Show place nature page and don't check "Je souhaite apporter des précisions sur le lieu des faits" checkbox
        Given I am on "/nature-de-lieu"
        And I should see 1 "body" element
        And I should see the key "more.info.place" translated
        And I should not see a "#place_nature_moreInfoText" element

    @javascript
    Scenario: Show place nature page and check "Je souhaite apporter des précisions sur le lieu des faits" checkbox
        Given I am on "/nature-de-lieu"
        And I should see 1 "body" element
        And I should see the key "more.info.place" translated
        And I should not see a "#place_nature_moreInfoText" element
        When I click the "label[for='place_nature_moreInfo']" element
        And I wait for the element "#place_nature_moreInfoText" to appear
        Then I should see a "#place_nature_moreInfoText" element

    @func
    Scenario Outline: I can see the offense exact date known radio buttons
        Given I am on "/nature-de-lieu"
        Then I should see 2 "input[type=radio][name='place_nature[exactDateKnown]']" elements
        And I should see "<exact_date_known>" in the "<element>" element

        Examples:
            | element                                  | exact_date_known |
            | label[for=place_nature_exactDateKnown_0] | Oui              |
            | label[for=place_nature_exactDateKnown_1] | Non              |

    @javascript
    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        Given I am on "/nature-de-lieu"
        When I click the "label[for='place_nature_exactDateKnown_0']" element
        And I wait for the element "#place_nature_startDate" to appear
        Then I should see the key "offense.unique.date" translated
        And I should see the key "offense.unique.date" translated
        And I should see a "input#place_nature_startDate" element

    @javascript
    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        Given I am on "/nature-de-lieu"
        When I click the "label[for='place_nature_exactDateKnown_1']" element
        And I wait for the element "#place_nature_startDate" to appear
        Then I should see the key "offense.unique.date" translated
        And I should see the key "offense.start.date" translated
        And I should see a "input#place_nature_startDate" element
        And I should see the key "offense.end.date" translated
        And I should see a "input#place_nature_endDate" element
