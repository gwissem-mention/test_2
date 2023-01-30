@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the offense facts step page

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I am on "/porter-plainte?france_connected=1"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        When I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_1]" element
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_2]" element
        And I press "facts_submit"
        And I wait 2000 ms
        
    Scenario: I can add an input text when I click on the add an object button
        When I press "objects_objects_add"
        Then I should see the key "pel.object" translated
        And I should see the key "pel.delete" translated
        And I should see the key "pel.object.category" translated
        And I should see the key "pel.amount" translated

    Scenario: I can see a list of text fields translated when I select "Multimédia" from category object list
        When I select "Multimédia" from "objects_objects_0_category"
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.phone.number.line" translated
        And I should see the key "pel.operator" translated
        And I should see the key "pel.serial.number" translated
        And I should see the key "pel.serial.number.help" translated
        And I should see the key "pel.amount" translated

    Scenario: I can see a list of text fields translated when I select "Moyens de paiement" from category object list
        When I select "Moyens de paiement" from "objects_objects_0_category"
        Then I should see the key "pel.organism.bank" translated
        And I should see the key "pel.bank.account.number" translated
        And I should see the key "pel.credit.card.number" translated
        And I should see the key "pel.amount" translated

    Scenario: I can delete an input text when I click on the delete an object button
        And  I press "objects_objects_add"
        When I press "objects_objects_1_delete"
        Then I should not see a "input#objects_objects_1_label" element

    Scenario: I should see 2 inputs when I select "Other" for object category
        When I select "6" from "objects_objects_0_category"
        Then I should see the key "pel.description" translated
        And I should see the key "pel.quantity" translated
        And I should see a "input#objects_objects_0_description" element
        And I should see a "input#objects_objects_0_quantity" element
        And I should see the key "pel.amount.for.group" translated

    Scenario: I can see a list of text fields translated when I select "Véhicules immatriculés" from category object list
        When I select "Véhicules immatriculés" from "objects_objects_0_category"
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.registration.number" translated
        And I should see the key "pel.registration.number.country" translated
        And I should see the key "pel.insurance.company" translated
        And I should see the key "pel.insurance.number" translated
        And I should see the key "pel.amount" translated

    Scenario: I should see an objects quantity / amount text when I fill an object form
        When I select "6" from "objects_objects_0_category"
        And I fill in "objects_objects_0_quantity" with "10"
        And I fill in "objects_objects_0_amount" with "99.99"
        And I press "objects_objects_add"
        And I select "6" from "objects_objects_1_category"
        And I fill in "objects_objects_1_quantity" with "10"
        And I fill in "objects_objects_1_amount" with "100"
        Then I should see "Vous avez ajouté 20 objets pour un montant total de 199,99 €"

    Scenario: Submit the complaint form as a victim logged in with France Connect
        And I select "1" from "objects_objects_0_category"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "1" from "objects_objects_1_category"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"
        Then the "#additional_information_accordion_item" element should contain "style=\"display: block;\""
