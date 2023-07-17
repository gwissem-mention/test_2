@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the end step form

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I follow "complaint_identity_link"
        Then I should be on "/porter-plainte/identite"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_familySituation"
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
        And I click the "label[for=additional_information_witnessesPresent_0]" element
        And I fill in "additional_information_witnesses_0_description" with "Jean DUPONT"
        And I fill in "additional_information_witnesses_0_email" with "jean.dupont@example.com"
        And I fill in "additional_information_witnesses_0_phone_number" with "0602030405"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        When I press "Continuer"
        And I follow "Je confirme"
        Then I should be on "/porter-plainte/rendez-vous"
        And I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"

    Scenario: I can click on the "Je donne mon avis" button and on the back to homepage button
        When I follow "Je donne mon avis"
        Then I should be on JeDonneMonAvis
        Given I am on "/porter-plainte/fin"
        When I follow "Retour à l’accueil"
        And I should be on "/"
