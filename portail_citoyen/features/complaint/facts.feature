@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the facts step form

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_familySituation"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: I can click on the back button
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/identite"

    Scenario: I can see the offense exact date known radio buttons
        And I should see 2 "input[type=radio][name='facts[offenseDate][exactDateKnown]']" elements
        And I should see "Oui" in the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I should see "Non" in the "label[for=facts_offenseDate_exactDateKnown_1]" element

    Scenario: I can see the offense choice hour radio buttons
        And I should see 3 "input[type=radio][name='facts[offenseDate][choiceHour]']" elements
        And I should see "Oui" in the "label[for=facts_offenseDate_choiceHour_0]" element
        And I should see "Non" in the "label[for=facts_offenseDate_choiceHour_1]" element
        And I should see "Non mais j’ai le créneau horaire" in the "label[for=facts_offenseDate_choiceHour_2]" element

    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        Then I should see the key "pel.complaint.exact.date" translated
        And I should see a "input#facts_offenseDate_startDate" element

    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        Then I should see the key "pel.between" translated
        And I should see a "input#facts_offenseDate_startDate" element
        And I should see the key "pel.and" translated
        And I should see a "input#facts_offenseDate_endDate" element

    Scenario: I can see only the hour input When I wait and select "Oui" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I should see a "input#facts_offenseDate_hour" element
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element

    Scenario: I can see only the hour input When I wait and select "Oui" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        Then I should see a "input#facts_offenseDate_startHour" element
        And I should see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see only the hour input When I wait and select "Oui" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element
        And I should see a "input#facts_offenseDate_hour" element

    Scenario: I can see only the start hour input When I fill the end hour input
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        And I fill in "facts_offenseDate_endHour" with "15:00"
        Then I should see a "input#facts_offenseDate_startHour" element

    Scenario: I can see 1 date input if I select "Yes" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        Then I should see the key "pel.complaint.exact.date" translated
        And I should see a "input#facts_offenseDate_startDate" element

    Scenario: I can see 2 date inputs if I select "No" to offense exact date known radio buttons
        When I click the "label[for=facts_offenseDate_exactDateKnown_1]" element
        Then I should see the key "pel.between" translated
        And I should see a "input#facts_offenseDate_startDate" element
        And I should see the key "pel.and" translated
        And I should see a "input#facts_offenseDate_endDate" element

    Scenario: I can see only the hour input When I wait and select "Oui" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_0]" element
        Then I should see a "input#facts_offenseDate_hour" element
        And I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element

    Scenario: I can see only the hour input When I wait and select "Oui" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_2]" element
        Then I should see a "input#facts_offenseDate_startHour" element
        And I should see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see only the hour input When I wait and select "Oui" to the offense hour radio buttons
        When I click the "label[for=facts_offenseDate_choiceHour_1]" element
        Then I should not see a "input#facts_offenseDate_startHour" element
        And I should not see a "input#facts_offenseDate_endHour" element
        And I should not see a "input#facts_offenseDate_hour" element

    Scenario: I can see 2 inputs text if I select "Yes" to addressOrRouteFactsKnown radio button
        When I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        Then I should see the key "pel.address.start.or.exact" translated
        And I should see the key "pel.address.end" translated
        And I should see a "input#facts-startAddress-address" element
        And I should see a "input#facts-endAddress-address" element

    Scenario: I should not see 2 inputs text if I select "No" to addressOrRouteFactsKnown radio button
        When I click the "label[for=facts_address_addressOrRouteFactsKnown_1]" element
        Then I should not see the key "pel.address.start.or.exact" translated
        And I should not see the key "pel.address.end" translated

    Scenario: Submit the facts form
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts-startAddress-address" with "1 test street"
        And I fill in "facts-startAddress-address" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I select "1" from "facts_placeNature"
        And I select "5" from "facts_subPlaceNature"
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
        And I click the "label[for=facts_offenseDate_choiceHour_2]" element
        And I fill in "facts_offenseDate_startHour" with "18:00"
        And I fill in "facts_offenseDate_endHour" with "16:00"
        Then I should see the key "pel.start.hour.after.end.hour" translated

    Scenario: I should see a error when I put a offense end hour = offense start hour
        When I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2023"
        And I click the "label[for=facts_offenseDate_choiceHour_2]" element
        And I fill in "facts_offenseDate_startHour" with "13:00"
        And I fill in "facts_offenseDate_endHour" with "13:00"
        Then I should see the key "pel.start.hour.same.as.end.hour" translated

    Scenario: The field start address should be filled with identity address when place nature is home and addressOrRouteFactsKnown is checked
        And I select "1" from "facts_placeNature"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        Then the "facts-startAddress-address" field should contain "Avenue de la République 75011 Paris"

    Scenario: The Google Maps should not be displays at init
        Then I should not see a "div#facts-map" element

    Scenario: A Google Maps should be displayed on the facts page when I select "Voie publique" or "Parking" nature place
        And I select "2" from "facts_placeNature"
        Then I should see a "div#facts-map" element
        And I should see a ".gm-style" element
        When I select "4" from "facts_placeNature"
        Then I should see a "div#facts-map" element
        And I should see a ".gm-style" element

    Scenario: I can fill the map autocomplete and the marker should be at the right place
        And I select "2" from "facts_placeNature"
        And I fill in the map autocomplete "map-search" with "81 Avenue de la République 75011 Paris" and click on the first result
        Then the marker should be at latitude "48.8641471" and longitude "2.3815167"

    Scenario: The callingPhone field should appear, the addressOrRouteFactsKnown / start address and end address should not be displayed when I select "Téléphone" as place nature
        And I select "3" from "facts_placeNature"
        Then I should not see a "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I should not see a "input#facts-startAddress-address" element
        And I should not see a "input#facts-endAddress-address" element
        And I should see a "input#facts_callingPhone_number" element
        And I should not see a "div#facts-map" element

    Scenario: I can fill the map autocomplete and the address should be in the form field
        When I select "4" from "facts_placeNature"
        And I fill in the map autocomplete "map-search" with "5 Rue Emile Zola 37000 Tours" and click on the first result
        Then the "facts-startAddress-address" field should contain "5 Rue Émile Zola 37000 Tours"

    Scenario: I select "Parking" nature place, I can see a Google Maps and an exact address field
        When I select "4" from "facts_placeNature"
        Then I should see a "div#facts-map" element
        And I should see a ".gm-style" element
        And I should see the key "pel.address.exact" translated
        And I should see a "#facts-startAddress-address" element
        And I should not see a "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I should not see a "label[for=facts_address_addressOrRouteFactsKnown_1]" element
        And I should not see the key "pel.address.start.or.exact" translated
        And I should not see a "#facts-endAddress-address" element
