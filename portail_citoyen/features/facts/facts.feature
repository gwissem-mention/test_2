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
        And I should see the key "pel.address.or.route.facts" translated
        And I should see the key "pel.additional.place.information" translated
        And I should see a "#facts_address_addressAdditionalInformation" element
        And I should not see a "#place_nature_moreInfoText" element
        And I should see the key "pel.complaint.exact.date.known" translated
        And I should see the key "pel.do.you.know.hour.facts" translated
        And I should see the key "pel.additional.factual.information" translated
        And I should see the key "pel.do.you.have.informations.on.potential.suspects" translated
        And I should see the key "pel.facts.witnesses" translated
        And I should see the key "pel.additional.factual.information" translated
        And I should see the key "pel.fsi.visit" translated
        And I should see the key "pel.cctv.present" translated
        And I should see the key "pel.object.category" translated
        And I should see the key "pel.object.category.choose" translated
        And I should see the key "pel.objects" translated
        And I should see the key "pel.objects.add" translated
        And I should see the key "pel.object" translated
        And I should see the key "pel.facts.description.precise" translated
        And I should see the key "pel.is.amount.known" translated

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
            | Vol et Dégradation       |
            | Autre atteinte aux biens |

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

    @func
    Scenario Outline: I can see the object category choice list
        Given I am on "/faits"
        When I select "<object_category>" from "facts_objects_0_category"
        Then I should see "<object_category>" in the "#facts_objects_0_category" element

        Examples:
            | object_category            |
            | Documents                  |
            | Moyens de paiement         |
            | Multimédia                 |
            | Véhicules immatriculés     |
            | Véhicules non immatriculés |
            | Autres                     |

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

    @func
    Scenario Outline: I can see the suspectsChoice choices
        Given I am on "/faits"
        Then I should see 2 "input[type=radio][name='facts[additionalInformation][suspectsChoice]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                                 | choice |
            | label[for=facts_additionalInformation_suspectsChoice_0] | Oui    |
            | label[for=facts_additionalInformation_suspectsChoice_1] | Non    |

    @javascript
    Scenario: Selecting the "label[for=facts_additionalInformation_suspectsChoice_1]" element and not showing
    the key "pel.facts.suspects.informations.text" translated
        Given I am on "/faits"
        When I click the "label[for=facts_additionalInformation_suspectsChoice_1]" element
        And I should not see the key "pel.facts.suspects.informations.text" translated

    @func
    Scenario Outline: I can see the witnesses choices
        Given I am on "/faits"
        Then I should see 2 "input[type=radio][name='facts[additionalInformation][witnesses]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                            | choice |
            | label[for=facts_additionalInformation_witnesses_0] | Oui    |
            | label[for=facts_additionalInformation_witnesses_1] | Non    |

    @javascript
    Scenario: I can see 1 text input if I select "Yes" to the witnesses radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_additionalInformation_witnesses_0]" element
        And I wait for the element "#facts_additionalInformation_witnessesText" to appear
        Then I should see the key "pel.facts.witnesses.information.text" translated
        And I should see a "input#facts_additionalInformation_witnessesText" element

    @func
    Scenario Outline: I can see the fsi visit choices
        Given I am on "/faits"
        Then I should see 2 "input[type=radio][name='facts[additionalInformation][fsiVisit]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                           | choice |
            | label[for=facts_additionalInformation_fsiVisit_0] | Oui    |
            | label[for=facts_additionalInformation_fsiVisit_1] | Non    |

    @javascript
    Scenario: I can see 1 radio button group if I select "Yes" to the fsi visit radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_additionalInformation_fsiVisit_0]" element
        And I wait for the element "#facts_additionalInformation_observationMade_0" to appear
        Then I should see the key "pel.observation.made" translated
        And I should see a "input#facts_additionalInformation_observationMade_0" element
        And I should see a "input#facts_additionalInformation_observationMade_1" element


    @func
    Scenario Outline: I can see the cctv choices
        Given I am on "/faits"
        Then I should see 3 "input[type=radio][name='facts[additionalInformation][cctvPresent]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                              | choice         |
            | label[for=facts_additionalInformation_cctvPresent_0] | Oui            |
            | label[for=facts_additionalInformation_cctvPresent_1] | Non            |
            | label[for=facts_additionalInformation_cctvPresent_2] | Je ne sais pas |

    @javascript
    Scenario: I can see 1 radio button group if I select "Yes" to the cctv present radio buttons
        Given I am on "/faits"
        When I click the "label[for=facts_additionalInformation_cctvPresent_0]" element
        And I wait for the element "#facts_additionalInformation_cctvAvailable_0" to appear
        Then I should see the key "pel.cctv.available" translated
        And I should see a "input#facts_additionalInformation_cctvAvailable_0" element
        And I should see a "input#facts_additionalInformation_cctvAvailable_1" element

    @javascript
    Scenario: I can add an input text when I click on the add an object button
        Given I am on "/faits"
        When I press "facts_objects_add"
        Then I should see the key "pel.object" translated
        And I should see the key "pel.delete" translated
        And I should see a "input#facts_objects_1_label" element

    @javascript
    Scenario: I can a list of text fields translated when I select "Multimédia" from category object list
        Given I am on "/faits"
        When I select "Multimédia" from "facts_objects_0_category"
        And I wait for the element "#facts_objects_0_brand" to appear
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.phone.number.line" translated
        And I should see the key "pel.operator" translated
        And I should see the key "pel.serial.number" translated
        And I should see the key "pel.serial.number.help" translated

    @javascript
    Scenario: I can delete an input text when I click on the delete an object button
        Given I am on "/faits"
        And  I press "facts_objects_add"
        And I wait for the element "#facts_objects_1_delete" to appear
        When I press "facts_objects_1_delete"
        And I wait for the element "input#facts_objects_1_label" to disappear
        Then I should not see a "input#facts_objects_1_label" element

    @javascript
    Scenario: I can see 1 number input if I select "Yes" to amount known radio button
        Given I am on "/faits"
        When I click the "label[for=facts_amountKnown_0]" element
        And I wait for the element "#facts_amount" to appear
        Then I should see the key "pel.amount" translated
        And I should see a "input#facts_amount" element

    @javascript
    Scenario: I can see 2 inputs text if I select "Yes" to isAddressOrRouteFactsKnown radio button
        Given I am on "/faits"
        When I click the "label[for=facts_address_isAddressOrRouteFactsKnown_0]" element
        And I wait for the element "#facts_address_startAddress" to appear
        Then I should see the key "pel.address.start.or.exact" translated
        And I should see the key "pel.address.end" translated
        And I should see a "input#facts_address_startAddress" element
        And I should see a "input#facts_address_endAddress" element

    @javascript
    Scenario: I should not see 2 inputs text if I select "No" to isAddressOrRouteFactsKnown radio button
        Given I am on "/faits"
        When I click the "label[for=facts_address_isAddressOrRouteFactsKnown_1]" element
        Then I should not see the key "pel.address.start.or.exact" translated
        And I should not see the key "pel.address.end" translated

    @javascript
    Scenario: I should see 2 inputs when I select "Other" for object category
        Given I am on "/faits"
        When I select "6" from "facts_objects_0_category"
        Then I should see the key "pel.description" translated
        And I should see the key "pel.quantity" translated
        And I should see a "input#facts_objects_0_description" element
        And I should see a "input#facts_objects_0_quantity" element

    @javascript
    Scenario: Submit the facts form
        Given I am on "/faits"
        When I wait and select "1" from "facts_offenseNature_offenseNature"
        And I click the "label[for=facts_address_isAddressOrRouteFactsKnown_0]" element
        And I wait for the element "#facts_address_startAddress" to appear
        And I wait and fill in "facts_address_startAddress" with "1 test street"
        And I wait and fill in "facts_address_endAddress" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I wait and fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I wait and fill in "facts_offenseDate_hour" with "15:00"
        And I fill in "facts_objects_0_label" with "Object 1"
        And I press "facts_objects_add"
        And I wait and fill in "facts_objects_1_label" with "Object 2"
        And I click the "label[for=facts_amountKnown_0]" element
        And I wait for the element "#facts_amount" to appear
        And I wait and fill in "facts_amount" with "700"
        And I click the "label[for=facts_additionalInformation_suspectsChoice_0]" element
        And I wait and fill in "facts_additionalInformation_suspectsText" with "informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=facts_additionalInformation_witnesses_0]" element
        And I wait and fill in "facts_additionalInformation_witnessesText" with "informations"
        And I click the "label[for=facts_additionalInformation_fsiVisit_0]" element
        And I click the "label[for=facts_additionalInformation_observationMade_0]" element
        And I click the "label[for=facts_additionalInformation_cctvPresent_0]" element
        And I wait for the element "#facts_additionalInformation_cctvAvailable_0" to appear
        And I click the "label[for=facts_additionalInformation_cctvAvailable_0]" element
        And I wait and fill in "facts_description" with "informations"
        And I press "Suivant"
        Then I am redirected on "/recapitulatif"
