@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the facts step form

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        And I should be on "/porter-plainte/faits"

    Scenario: I can click on the back button
        When I follow "Précédent"
        Then I should be on "/porter-plainte/identite"

    Scenario: I can see the offense nature checkboxes
        Then I should see 3 "input[type=checkbox][name='facts[offenseNature][offenseNatures][]']" elements
        And I should see "Vol" in the "label[for=facts_offenseNature_offenseNatures_0]" element
        And I should see "Dégradation" in the "label[for=facts_offenseNature_offenseNatures_1]" element
        And I should see "Autre atteinte aux biens" in the "label[for=facts_offenseNature_offenseNatures_2]" element

    Scenario: I can see the place natures list
        Then I should see "Domicile/Logement" in the "#facts_placeNature" element
        And I should see "Parking / garage" in the "#facts_placeNature" element
        And I should see "Voie publique / Rue" in the "#facts_placeNature" element
        And I should see "Commerce" in the "#facts_placeNature" element
        And I should see "Transports en commun" in the "#facts_placeNature" element
        And I should see "Autres natures de lieu" in the "#facts_placeNature" element
        And I should see "Lieu indéterminé" in the "#facts_placeNature" element

    Scenario: I can see the offense exact date known radio buttons
        Then I should see 2 "input[type=radio][name='facts[offenseDate][exactDateKnown]']" elements
        And I should see "Oui" in the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I should see "Non" in the "label[for=facts_offenseDate_exactDateKnown_1]" element

    Scenario: I can see the offense choice hour radio buttons
        Then I should see 3 "input[type=radio][name='facts[offenseDate][choiceHour]']" elements
        And I should see "Oui je connais l'heure exacte des faits" in the "label[for=facts_offenseDate_choiceHour_0]" element
        And I should see "Non mais je connais le créneau horaire" in the "label[for=facts_offenseDate_choiceHour_1]" element
        And I should see "Je ne connais pas l'heure des faits" in the "label[for=facts_offenseDate_choiceHour_2]" element

    Scenario: I can see a warning text if I select "Robbery"
        When I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        Then I should see the key "pel.complaint.offense.nature.warning.text" translated

    Scenario: I can see a textarea field if I select Other AAB
        When I click the "label[for=facts_offenseNature_offenseNatures_2]" element
        And I should see the key "pel.complaint.offense.nature.other.aab.text" translated

    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.the" translated
        And I should see a "input#facts_offenseDate_startDate" element

    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.between" translated
        And I should see a "input#facts_offenseDate_startDate" element
        And I should see the key "pel.and" translated
        And I should see a "input#facts_offenseDate_endDate" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I should see a "input#facts_offenseDate_hour" element
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_1]" element
        Then I should see a "input#facts_offenseDate_startHour" element
        And I should see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        Then I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see a warning text if I select "Robbery"
        When I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        Then I should see the key "pel.complaint.offense.nature.warning.text" translated

    Scenario: I can see a textarea field if I select Other AAB
        When I click the "label[for=facts_offenseNature_offenseNatures_2]" element
        And I should see the key "pel.complaint.offense.nature.other.aab.text" translated

    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.the" translated
        And I should see a "input#facts_offenseDate_startDate" element

    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        Then I should see the key "pel.the" translated
        And I should see the key "pel.between" translated
        And I should see a "input#facts_offenseDate_startDate" element
        And I should see the key "pel.and" translated
        And I should see a "input#facts_offenseDate_endDate" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I should see a "input#facts_offenseDate_hour" element
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_1]" element
        Then I should see a "input#facts_offenseDate_startHour" element
        And I should see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see only the hour input When I wait and select "Oui je connais l'heure exacte des faits" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        Then I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see 2 inputs text if I select "Yes" to addressOrRouteFactsKnown radio button
        When I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        Then I should see the key "pel.address.start.or.exact" translated
        And I should see the key "pel.address.end" translated
        And I should see a "input#facts_address_startAddress" element
        And I should see a "input#facts_address_endAddress" element

    Scenario: I should not see 2 inputs text if I select "No" to addressOrRouteFactsKnown radio button
        When I click the "label[for=facts_address_addressOrRouteFactsKnown_1]" element
        Then I should not see the key "pel.address.start.or.exact" translated
        And I should not see the key "pel.address.end" translated

    Scenario: Submit the facts form
        When I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts_address_startAddress" with "1 test street"
        And I fill in "facts_address_endAddress" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"

    Scenario: I should see a error when I put a offense end date < offense start date
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2023"
        And I fill in "facts_offenseDate_endDate" with "15/12/2022"
        Then I should see the key "pel.start.date.after.end.date" translated

    Scenario: I should see a error when I put a offense end date = offense start date
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2023"
        And I fill in "facts_offenseDate_endDate" with "01/01/2023"
        Then I should see the key "pel.start.date.same.as.end.date" translated

    Scenario: I should see a error when I put a offense end hour < offense start hour
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2023"
        And I click the "label[for=facts_offenseDate_choiceHour_1]" element
        And I fill in "facts_offenseDate_startHour" with "18:00"
        And I fill in "facts_offenseDate_endHour" with "16:00"
        Then I should see the key "pel.start.hour.after.end.hour" translated

    Scenario: I should see a error when I put a offense end hour = offense start hour
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2023"
        And I click the "label[for=facts_offenseDate_choiceHour_1]" element
        And I fill in "facts_offenseDate_startHour" with "13:00"
        And I fill in "facts_offenseDate_endHour" with "13:00"
        Then I should see the key "pel.start.hour.same.as.end.hour" translated

