@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the appointment step form

    Background:
        Given I am on "/porter-plainte/statut-declarant"
        And I click the "label[for=declarant_status_declarantStatus_0]" element
        And I press "declarant_status_submit"
        And I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/identite"
        Given I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts-startAddress-address" with "1 test street"
        And I fill in "facts-startAddress-address" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        Given I select "5" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "5" from "objects_objects_1_category"
        And I select "1" from "objects_objects_1_status"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnesses_0]" element
        And I fill in "additional_information_witnessesText" with "witnesses informations"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        When I press "Suivant"
        And I follow "Je confirme"
        Then I should be on "/porter-plainte/rendez-vous"

    Scenario: I can click on the back button
        When I follow "Précédent"
        Then I should be on "/porter-plainte/recapitulatif"

    Scenario: I can fill the appointment textarea and submit
        When I fill in "appointment_appointmentContactText" with "Between 10am and 12am"
        And I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"

    Scenario: I can submit with no filling the appointment textarea
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"

    Scenario: I should see error if I valid with no filling the appointment textarea when I am victim of violence
        Given I am on "/porter-plainte/faits"
        When I click the "label[for=facts_victimOfViolence]" element
        And I fill in "facts_victimOfViolenceText" with "Violence informations"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        Given I am on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see error if I valid with no filling the appointment textarea when an object is a stolen registered vehicle
        Given I am on "/porter-plainte/objets"
        When I select "Véhicules immatriculés" from "objects_objects_0_category"
        And I fill in "objects_objects_0_brand" with "Renault"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        Given I am on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see error if I valid with no filling the appointment textarea when I am not france connected
        Given I am on "authentification"
        When I follow "no_france_connect_auth_button"
        And I follow "Je confirme"
        Then I should be on "/porter-plainte/identite"
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts-startAddress-address" with "1 test street"
        And I fill in "facts-startAddress-address" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        Given I select "5" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "5" from "objects_objects_1_category"
        And I select "1" from "objects_objects_1_status"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnesses_0]" element
        And I fill in "additional_information_witnessesText" with "witnesses informations"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        When I press "Suivant"
        And I follow "Je confirme"
        Then I should be on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see error if I valid with no filling the appointment textarea when I am person legal representative
        Given I am on "/porter-plainte/statut-declarant"
        And I click the "label[for=declarant_status_declarantStatus_1]" element
        When I press "declarant_status_submit"
        Then I should be on "/authentification"
        Given I am on "/porter-plainte/identite"
        When I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "represented-person-address" with "avenue de la république paris"
        And I click the "#represented-person-address-75111_8158" element
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        Given I am on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see error if I valid with no filling the appointment textarea when I am corporation legal representative
        Given I am on "/porter-plainte/statut-declarant"
        And I click the "label[for=declarant_status_declarantStatus_2]" element
        When I press "declarant_status_submit"
        Then I should be on "/authentification"
        Given I am on "/porter-plainte/identite"
        When I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone_number" with "0102020304"
        And I fill in "corporation-address" with "avenue de la république paris"
        And I click the "#corporation-address-38485_0570" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        Given I am on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see a search input, a google maps and a appointment detail text textarea
        Then I should see a "div#appointment-map" element
        And I should see a ".gm-style" element
        And I should see a "textarea#appointment_appointmentContactText" element
        And I should see a "input#map-search" element

    Scenario: I should see units markers when I search in the search input
        When I fill in the map autocomplete "map-search" with "voiron" and click on the first result
        Then I should see 2 ".unit-list-item" element
        And I should see "2 résultats"
        When I click the "li[data-unit-id-anonym=1008950] .unit-select" element
        Then I should see a "li[data-unit-id-anonym=1008950] .unit-unselect" element
        Then I should not see a "li[data-unit-id-anonym=1008950] .unit-unselect.fr-hidden" element
        And I should see a "li[data-unit-id-anonym=1008950] .unit-select.fr-hidden" element
        And I should see the key "pel.chosen" translated

    Scenario: I can pick a unit and submit the page
        When I fill in the map autocomplete "map-search" with "voiron" and click on the first result
        And I click the "li[data-unit-id-anonym=1008950] .unit-select" element
        And I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"
        Given I am on "/porter-plainte/recapitulatif"
        Then I should see "Brigade de proximité de Renage - Renage"

    Scenario: I can unpick a unit
        When I fill in the map autocomplete "map-search" with "voiron" and click on the first result
        And I click the "li[data-unit-id-anonym=1008950] .unit-select" element
        And I click the "li[data-unit-id-anonym=1008950] .unit-unselect" element
        Then I should see a "li[data-unit-id-anonym=1008950] .unit-unselect.fr-hidden" element
        And I should not see a "li[data-unit-id-anonym=1008950] .unit-select.fr-hidden" element

    Scenario: I can click on a marker and see the unit details on the left menu
        When I fill in the map autocomplete "map-search" with "voiron" and click on the first result
        And I click on the marker at index 1008950
        Then I should not see a "li[data-unit-id-anonym=1008950] .unit-unselect.fr-hidden" element
        And I should see a "li[data-unit-id-anonym=1008950] .unit-select.fr-hidden" element
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"
        Given I am on "/porter-plainte/recapitulatif"
        Then I should see "Brigade de proximité de Renage - Renage"

