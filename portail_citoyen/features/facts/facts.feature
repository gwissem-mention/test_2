Feature:
    In order to fill a complaint
    As a user
    I want to see the offense facts step page

    @func
    Scenario: I can see the offense facts page
        Given I am on "/faits"
        Then the response status code should be 200
        And I should see the key "pel.ministry" translated
        And I should see the key "pel.inside" translated
        And I should see the key "pel.and.overseas" translated
        And I should see the key "pel.complaint.nature.of.the.facts" translated
        And I should see the key "pel.nature.place" translated
        And I should see the key "pel.more.info.place" translated
        And I should not see a "#place_nature_moreInfoText" element
        And I should see the key "pel.complaint.exact.date.known" translated
        And I should see the key "pel.do.you.know.hour.facts" translated
        And I should see the key "pel.additional.factual.information" translated
        And I should see the key "pel.do.you.have.informations.on.potential.suspects" translated
        And I should see the key "pel.facts.witnesses" translated

    @func
    Scenario: I can click on the identity breadcrumb
        Given I am on "/faits"
        When I follow "Identité"
        Then the response status code should be 200
        And I should be on "/identite"

    @func
    Scenario: I can see the offense nature breadcrumb
        Given I am on "/faits"
        Then I should see "Faits" in the ".fr-breadcrumb__list" element

    @func
    Scenario: I can click on the previous button
        Given I am on "/faits"
        When I follow "Précédent"
        Then the response status code should be 200
        And I should be on "/identite"

    @func
    Scenario Outline: I can see the offense nature list
        Given I am on "/faits"
        When I select "<offense_nature>" from "facts_offenseNature_offenseNature"
        Then I should see "<offense_nature>" in the "#facts_offenseNature_offenseNature" element

        Examples:
            | offense_nature           |
            | Vol                      |
            | Dégradation              |
            | Autre atteinte aux biens |

    @func
    Scenario Outline: I can see the offense places list
        Given I am on "/faits"
        When I select "<nature_place>" from "facts_placeNature_place"
        Then I should see "<nature_place>" in the "#facts_placeNature_place" element

        Examples:
            | nature_place           |
            | Domicile/Logement      |
            | Parking / garage       |
            | Voie publique / Rue    |
            | Commerce               |
            | Transports en commun   |
            | Autres natures de lieu |
            | Lieu indéterminé       |

    @func
    Scenario Outline: I can see the offense exact date known radio buttons
        Given I am on "/faits"
        Then I should see 2 "input[type=radio][name='facts[offenseDate][exactDateKnown]']" elements
        And I should see "<exact_date_known>" in the "<element>" element

        Examples:
            | element                                       | exact_date_known |
            | label[for=facts_offenseDate_exactDateKnown_0] | Oui              |
            | label[for=facts_offenseDate_exactDateKnown_1] | Non              |

    @func
    Scenario Outline: I can see the offense choice hour radio buttons
        Given I am on "/faits"
        Then I should see 3 "input[type=radio][name='facts[offenseDate][choiceHour]']" elements
        And I should see "<choice_hour>" in the "<element>" element

        Examples:
            | element                                   | choice_hour                             |
            | label[for=facts_offenseDate_choiceHour_0] | Oui je connais l'heure exacte des faits |
            | label[for=facts_offenseDate_choiceHour_1] | Non mais je connais le créneau horaire  |
            | label[for=facts_offenseDate_choiceHour_2] | Je ne connais pas l'heure des faits     |

    @javascript
    Scenario: I can see a warning text if I select "Robbery"
        Given I am on "/faits"
        When I wait and select "Vol" from "facts_offenseNature_offenseNature"
        And I wait for the element "#facts_offenseNature_aabText" to appear
        Then I should see the key "pel.complaint.offense.nature.warning.text" translated

    @javascript
    Scenario: I can see a textarea field if I select Other AAB
        Given I am on "/faits"
        When I wait and select "Autre atteinte aux biens" from "facts_offenseNature_offenseNature"
        And I wait for the element "#offense_nature_aabText" to appear
        And I should see the key "pel.complaint.offense.nature.other.aab.text" translated

    @javascript
    Scenario: I can see the more info input text if I check "Je souhaite apporter des précisions sur le lieu des faits" checkbox
        Given I am on "/faits"
        When I click the "label[for=facts_placeNature_moreInfo]" element
        And I wait for the element "#facts_placeNature_moreInfoText" to appear
        Then I should see a "#facts_placeNature_moreInfoText" element

    @javascript
    Scenario Outline: I can see a list a common transports When I wait and select "Common transport" nature place
        Given I am on "/faits"
        When I wait and select "Transports en commun" from "facts_placeNature_place"
        And I should see "Transports en commun" in the "#facts_placeNature_place" element
        And I wait for the element "#facts_placeNature_naturePlacePublicTransportChoice" to appear
        And I wait and select "<nature_place_public_transport>" from "facts_placeNature_naturePlacePublicTransportChoice"
        Then I should see "<nature_place_public_transport>" in the "#facts_placeNature_naturePlacePublicTransportChoice" element

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
    Scenario Outline: I can see a list a other places When I wait and select "Other places" nature place
        Given I am on "/faits"
        When I wait and select "Autres natures de lieu" from "facts_placeNature_place"
        And I wait for the element "#facts_placeNature_naturePlaceOtherChoice" to appear
        Then I wait and select "<nature_place_other>" from "facts_placeNature_naturePlaceOtherChoice"
        And I should see "<nature_place_other>" in the "#facts_placeNature_naturePlaceOtherChoice" element

        Examples:
            | nature_place_other |
            | Camping            |
            | Restaurant         |
            | Parc               |
            | Marché             |


    @javascript
    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I wait for the element "#facts_offenseDate_startDate" to appear
        Then I should see the key "pel.offense.unique.date" translated
        And I should see the key "pel.offense.unique.date" translated
        And I should see a "input#facts_offenseDate_startDate" element

    @javascript
    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        And I wait for the element "input#facts_offenseDate_startDate" to appear
        Then I should see the key "pel.offense.unique.date" translated
        And I should see the key "pel.offense.start.date" translated
        And I should see a "input#facts_offenseDate_startDate" element
        And I should see the key "pel.offense.end.date" translated
        And I should see a "input#facts_offenseDate_endDate" element

    @javascript
    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I wait for the element "#input#facts_offenseDate_hour" to appear
        And I should see a "input#facts_offenseDate_hour" element
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element

    @javascript
    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_offenseDate_choiceHour_1]" element
        Then I wait for the element "input#facts_offenseDate_hour" to appear
        And I should see a "input#facts_offenseDate_startHour" element
        And I should see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    @javascript
    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        Then I wait for the element "input#facts_offenseDate_hour" to appear
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    @javascript
    Scenario: Selecting the "label[for=facts_additionalInformation_suspectsChoice_1]" element and not showing
    the key "pel.facts.suspects.informations.text" translated
        Given I am on "/faits"
        When I click the "label[for=facts_additionalInformation_suspectsChoice_1]" element
        And I should not see the key "pel.facts.suspects.informations.text" translated


    @javascript
    Scenario: I can see 1 text input if I select "Yes" to the witnesses radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_additionalInformation_witnesses_0]" element
        And I wait for the element "#facts_additionalInformation_witnessesText" to appear
        Then I should see the key "pel.facts.witnesses.information.text" translated
        And I should see a "input#facts_additionalInformation_witnessesText" element


    @javascript
    Scenario: Submit the facts form
        Given I am on "/faits"
        When I select "1" from "facts_offenseNature_offenseNature"
        And I select "1" from "facts_placeNature_place"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I wait for the element "#facts_offenseDate_startDate" to appear
        And I wait and fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I wait for the element "#facts_offenseDate_hour" to appear
        And I wait and fill in "facts_offenseDate_hour" with "15:00"
        And I click the "label[for=facts_additionalInformation_suspectsChoice_0]" element
        And I wait for the element "#facts_additionalInformation_suspectsText" to appear
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I wait and fill in "facts_additionalInformation_suspectsText" with "informations"
        And I click the "label[for=facts_additionalInformation_witnesses_0]" element
        And I wait for the element "#facts_additionalInformation_witnessesText" to appear
        And I wait and fill in "facts_additionalInformation_witnessesText" with "informations"
        And I wait and fill in "facts_description" with "informations"
        And I press "Suivant"
        Then I should be on "/rendez-vous" by js
