@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the facts step form

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I am on "/porter-plainte"
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
        #And I click the "#complaint_offense_nature_warning_text" to appear
        Then I should see the key "pel.complaint.offense.nature.warning.text" translated

    Scenario: I can see a textarea field if I select Other AAB
        Given I am on "/porter-plainte"
        When I click the "label[for=facts_offenseNature_offenseNatures_2]" element
        #And I wait for the element "#offense_nature_aabText" to appear
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

    @javascript
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