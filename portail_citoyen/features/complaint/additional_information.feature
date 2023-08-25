@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the additional information step form

    Background:
        Given I am on "/authentification"
        And I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I follow "complaint_identity_link"
        Then I should be on "/porter-plainte/identite"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_familySituation"
        Given I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
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
        And I wait for "#facts_offenseDate_startDate" to appear
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        Given I select "5" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "5" from "objects_objects_1_category"
        And I select "1" from "objects_objects_1_status"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"

    Scenario: I can click on the back button
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/objets"

    Scenario: I can see the suspectsChoice choices
        And I wait for "input[type=radio][name='additional_information[suspectsChoice]']" to appear
        And I should see 2 "input[type=radio][name='additional_information[suspectsChoice]']" elements
        And I should see "Oui" in the "label[for=additional_information_suspectsChoice_0]" element
        And I should see "Non" in the "label[for=additional_information_suspectsChoice_1]" element

    Scenario: I can see the witnesses choices
        And I wait for "input[type=radio][name='additional_information[witnessesPresent]']" to appear
        And I should see 2 "input[type=radio][name='additional_information[witnessesPresent]']" elements
        And I should see "Oui" in the "label[for=additional_information_witnessesPresent_0]" element
        And I should see "Non" in the "label[for=additional_information_witnessesPresent_1]" element

    Scenario: I can see the fsi visit choices
        And I wait for "input[type=radio][name='additional_information[fsiVisit]']" to appear
        And I should see 2 "input[type=radio][name='additional_information[fsiVisit]']" elements
        And I should see "Oui" in the "label[for=additional_information_fsiVisit_0]" element
        And I should see "Non" in the "label[for=additional_information_fsiVisit_1]" element

    Scenario: I can see the cctv choices
        And I wait for "input[type=radio][name='additional_information[cctvPresent]']" to appear
        And I should see 3 "input[type=radio][name='additional_information[cctvPresent]']" elements
        And I should see "Oui" in the "label[for=additional_information_cctvPresent_0]" element
        And I should see "Non" in the "label[for=additional_information_cctvPresent_1]" element
        And I should see "Je ne sais pas" in the "label[for=additional_information_cctvPresent_2]" element

    Scenario: Selecting the "label[for=additional_information_suspectsChoice_1]" element and not showing
    the key "pel.facts.suspects.informations.text" translated
        And I wait for "label[for=additional_information_suspectsChoice_1]" to appear
        When I click the "label[for=additional_information_suspectsChoice_1]" element
        And I should not see the key "pel.facts.suspects.informations.text" translated

    Scenario: I can see 3 text input if I select "Yes" to the witnesses radio buttons
        And I wait for "label[for=additional_information_witnessesPresent_0]" to appear
        When I click the "label[for=additional_information_witnessesPresent_0]" element
        Then I should see a "input#additional_information_witnesses_0_description" element
        And I should see a "input#additional_information_witnesses_0_email" element
        And I should see a "input#additional_information_witnesses_0_phone_number" element

    Scenario: I can see 1 radio button group if I select "Yes" to the fsi visit radio buttons
        And I wait for "label[for=additional_information_fsiVisit_0]" to appear
        When I click the "label[for=additional_information_fsiVisit_0]" element
        Then I should see the key "pel.observation.made" translated
        And I should see a "input#additional_information_observationMade_0" element
        And I should see a "input#additional_information_observationMade_1" element

    Scenario: I can see 1 radio button group if I select "Yes" to the cctv present radio buttons
        And I wait for "label[for=additional_information_cctvPresent_0]" to appear
        When I click the "label[for=additional_information_cctvPresent_0]" element
        Then I should see the key "pel.cctv.available" translated
        And I should see a "input#additional_information_cctvAvailable_0" element
        And I should see a "input#additional_information_cctvAvailable_1" element

    Scenario: Submit the additional information form as a victim logged in with France Connect
        And I wait for "label[for=additional_information_suspectsChoice_0]" to appear
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnessesPresent_0]" element
        And I fill in "additional_information_witnesses_0_description" with "Jean DUPONT"
        And I fill in "additional_information_witnesses_0_email" with "jean.dupont@example.com"
        And I fill in "additional_information_witnesses_0_phone_number" with "0602030405"
        And I press "additional_information_witnesses_add"
        And I fill in "additional_information_witnesses_1_description" with "Thomas DURAND"
        And I fill in "additional_information_witnesses_1_email" with "thomas.durand@example.com"
        And I fill in "additional_information_witnesses_1_phone_number" with "0602030405"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        When I follow "Étape précédente"
        Then I am on "/porter-plainte/informations-complementaires"
        And the "additional_information_suspectsChoice_0" field should contain "1"
        And the "additional_information_suspectsText" field should contain "suspects informations"
        And the "additional_information_witnessesPresent_0" field should contain "1"
        And the "additional_information_witnesses_0_description" field should contain "Jean DUPONT"
        And the "additional_information_witnesses_0_email" field should contain "jean.dupont@example.com"
        And the "additional_information_witnesses_0_phone_number" field should contain "6 02 03 04 05"
        And the "additional_information_witnesses_1_description" field should contain "Thomas DURAND"
        And the "additional_information_witnesses_1_email" field should contain "thomas.durand@example.com"
        And the "additional_information_witnesses_1_phone_number" field should contain "6 02 03 04 05"
        And the "additional_information_fsiVisit_0" field should contain "1"
        And the "additional_information_observationMade_0" field should contain "1"
        And the "additional_information_cctvPresent_0" field should contain "1"
        And the "additional_information_cctvAvailable_0" field should contain "1"
