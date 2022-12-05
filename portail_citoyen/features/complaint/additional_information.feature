@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the additional information step form

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I am on "/porter-plainte?france_connected=1"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_mobile" with "0601020304"
        And I press "identity_submit"
        And I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts_address_startAddress" with "1 test street"
        And I fill in "facts_address_endAddress" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I press "facts_submit"
        And I select "1" from "objects_objects_0_category"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "1" from "objects_objects_1_category"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"

    Scenario: Selecting the "label[for=additional_information_suspectsChoice_1]" element and not showing
    the key "pel.facts.suspects.informations.text" translated
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_suspectsChoice_1]" element
        And I should not see the key "pel.facts.suspects.informations.text" translated

    Scenario: I can see 1 text input if I select "Yes" to the witnesses radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_witnesses_0]" element
        Then I should see the key "pel.facts.witnesses.information.text" translated
        And I should see a "input#additional_information_witnessesText" element

    Scenario: I can see 1 radio button group if I select "Yes" to the fsi visit radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_fsiVisit_0]" element
        Then I should see the key "pel.observation.made" translated
        And I should see a "input#additional_information_observationMade_0" element
        And I should see a "input#additional_information_observationMade_1" element

    Scenario: Selecting the "label[for=additional_information_suspectsChoice_1]" element and not showing the key "pel.facts.suspects.informations.text" translated
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_suspectsChoice_1]" element
        And I should not see the key "pel.facts.suspects.informations.text" translated

    Scenario: I can see 1 text input if I select "Yes" to the witnesses radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_witnesses_0]" element
        Then I should see the key "pel.facts.witnesses.information.text" translated
        And I should see a "input#additional_information_witnessesText" element

    Scenario: I can see 1 radio button group if I select "Yes" to the fsi visit radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_fsiVisit_0]" element
        Then I should see the key "pel.observation.made" translated
        And I should see a "input#additional_information_observationMade_0" element
        And I should see a "input#additional_information_observationMade_1" element

    Scenario: I can see 1 radio button group if I select "Yes" to the cctv present radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_cctvPresent_0]" element
        Then I should see the key "pel.cctv.available" translated
        And I should see a "input#additional_information_cctvAvailable_0" element
        And I should see a "input#additional_information_cctvAvailable_1" element

    Scenario: Submit the additional information form as a victim logged in with France Connect
        Given I am on "/porter-plainte"
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnesses_0]" element
        And I fill in "additional_information_witnessesText" with "witnesses informations"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I fill in "additional_information_description" with "description informations"
        And I press "additional_information_submit"
        And I wait 2000 ms
        Given I am on "/recapitulatif"
        When I follow "Précédent"
        Then I am on "/porter-plainte"
        And the "additional_information_suspectsChoice_0" field should contain "1"
        And the "additional_information_suspectsText" field should contain "suspects informations"
        And the "additional_information_witnesses_0" field should contain "1"
        And the "additional_information_witnessesText" field should contain "witnesses informations"
        And the "additional_information_fsiVisit_0" field should contain "1"
        And the "additional_information_observationMade_0" field should contain "1"
        And the "additional_information_cctvPresent_0" field should contain "1"
        And the "additional_information_cctvAvailable_0" field should contain "1"
        And the "additional_information_description" field should contain "description informations"
