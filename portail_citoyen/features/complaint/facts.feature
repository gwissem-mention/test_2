@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the facts step form

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
        And I click the "#facts_accordion_title" element

    Scenario: I can see a warning text if I select "Robbery"
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        Then I should see the key "pel.complaint.offense.nature.warning.text" translated

    Scenario: I can see a textarea field if I select Other AAB
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseNature_offenseNatures_2]" element
        And I should see the key "pel.complaint.offense.nature.other.aab.text" translated

    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.the" translated
        And I should see a "input#facts_offenseDate_startDate" element

    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.between" translated
        And I should see a "input#facts_offenseDate_startDate" element
        And I should see the key "pel.and" translated
        And I should see a "input#facts_offenseDate_endDate" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I should see a "input#facts_offenseDate_hour" element
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_choiceHour_1]" element
        Then I should see a "input#facts_offenseDate_startHour" element
        And I should see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        Then I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see a warning text if I select "Robbery"
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        Then I should see the key "pel.complaint.offense.nature.warning.text" translated

    Scenario: I can see a textarea field if I select Other AAB
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseNature_offenseNatures_2]" element
        And I should see the key "pel.complaint.offense.nature.other.aab.text" translated

    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.the" translated
        And I should see a "input#facts_offenseDate_startDate" element

    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.between" translated
        And I should see a "input#facts_offenseDate_startDate" element
        And I should see the key "pel.and" translated
        And I should see a "input#facts_offenseDate_endDate" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I should see a "input#facts_offenseDate_hour" element
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_choiceHour_1]" element
        Then I should see a "input#facts_offenseDate_startHour" element
        And I should see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        Then I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can add an input text when I click on the add an object button
        Given I am on "/porter-plainte"
        When I press "facts_objects_add"
        Then I should see the key "pel.object" translated
        And I should see the key "pel.delete" translated
        And I should see a "input#facts_objects_1_label" element

    Scenario: I can see a list of text fields translated when I select "Multimédia" from category object list
        Given I am on "/porter-plainte"
        When I select "Multimédia" from "facts_objects_0_category"
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.phone.number.line" translated
        And I should see the key "pel.operator" translated
        And I should see the key "pel.serial.number" translated
        And I should see the key "pel.serial.number.help" translated

    Scenario: I can see a list of text fields translated when I select "Moyens de paiement" from category object list
        Given I am on "/porter-plainte"
        When I select "Moyens de paiement" from "facts_objects_0_category"
        Then I should see the key "pel.organism.bank" translated
        And I should see the key "pel.bank.account.number" translated
        And I should see the key "pel.credit.card.number" translated

    Scenario: I can delete an input text when I click on the delete an object button
        Given I am on "/porter-plainte"
        And  I press "facts_objects_add"
        When I press "facts_objects_1_delete"
        Then I should not see a "input#facts_objects_1_label" element

    Scenario: I can see 1 number input if I select "Yes" to amount known radio button
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_amountKnown_0]" element
        Then I should see the key "pel.amount" translated
        And I should see a "input#facts_amount" element

    Scenario: I can see 2 inputs text if I select "Yes" to addressOrRouteFactsKnown radio button
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        Then I should see the key "pel.address.start.or.exact" translated
        And I should see the key "pel.address.end" translated
        And I should see a "input#facts_address_startAddress" element
        And I should see a "input#facts_address_endAddress" element

    Scenario: I should not see 2 inputs text if I select "No" to addressOrRouteFactsKnown radio button
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_address_addressOrRouteFactsKnown_1]" element
        Then I should not see the key "pel.address.start.or.exact" translated
        And I should not see the key "pel.address.end" translated

    Scenario: I should see 2 inputs when I select "Other" for object category
        Given I am on "/porter-plainte"
        When I select "6" from "facts_objects_0_category"
        Then I should see the key "pel.description" translated
        And I should see the key "pel.quantity" translated
        And I should see a "input#facts_objects_0_description" element
        And I should see a "input#facts_objects_0_quantity" element

    Scenario: I can see a list of text fields translated when I select "Véhicules immatriculés" from category object list
        Given I am on "/porter-plainte"
        When I select "Véhicules immatriculés" from "facts_objects_0_category"
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.registration.number" translated
        And I should see the key "pel.registration.number.country" translated
        And I should see the key "pel.insurance.company" translated
        And I should see the key "pel.insurance.number" translated

    Scenario: Submit the facts form as a victim logged in with France Connect
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts_address_startAddress" with "1 test street"
        And I fill in "facts_address_endAddress" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I select "1" from "facts_objects_0_category"
        And I fill in "facts_objects_0_label" with "Object 1"
        And I press "facts_objects_add"
        And I select "1" from "facts_objects_1_category"
        And I fill in "facts_objects_1_label" with "Object 2"
        And I click the "label[for=facts_amountKnown_0]" element
        And I fill in "facts_amount" with "700"
        And I press "facts_submit"
        Then the "#additional_information_accordion_item" element should contain "style=\"display: block;\""
