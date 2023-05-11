@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the offense facts step page

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/statut-declarant"
        And I click the "label[for=declarant_status_declarantStatus_0]" element
        And I press "declarant_status_submit"
        Then I should be on "/porter-plainte/identite"
        Given I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_1]" element
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_2]" element
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"

    Scenario: I can click on the back button
        When I follow "Précédent"
        Then I should be on "/porter-plainte/faits"

    Scenario: I can see the object category choice list
        And I should see "Document officiel" in the "#objects_objects_0_category" element
        And I should see "Moyens de paiement" in the "#objects_objects_0_category" element
        And I should see "Multimédia" in the "#objects_objects_0_category" element
        And I should see "Véhicules immatriculés " in the "#objects_objects_0_category" element
        And I should see "Véhicules non immatriculés" in the "#objects_objects_0_category" element
        And I should see "Autres" in the "#objects_objects_0_category" element

    Scenario: I can add an input text when I click on the add an object button
        When I press "objects_objects_add"
        Then I should see the key "pel.object" translated
        And I should see the key "pel.delete" translated
        And I should see the key "pel.object.category" translated
        And I should see the key "pel.object.status" translated
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
        And I should see the key "pel.object.status" translated

    Scenario: I can see a list of text fields translated when I select "Moyens de paiement" from category object list
        When I select "Moyens de paiement" from "objects_objects_0_category"
        Then I should see the key "pel.organism.bank" translated
        And I should see the key "pel.bank.account.number" translated
        And I should see the key "pel.credit.card.number" translated
        And I should see the key "pel.amount" translated
        And I should see the key "pel.object.status" translated

    Scenario: I can see a list of text fields translated when I select "Document officiel" from category object list
        When I select "Document officiel" from "objects_objects_0_category"
        Then I should see the key "pel.document.type" translated
        And I should see the key "pel.i.am.the.owner.of.this.document" translated
        And I should see the key "pel.object.status" translated
        And I should see the key "pel.amount" translated
        And I should see a "select#objects_objects_0_documentType_documentType" element
        And I should see a "input#objects_objects_0_documentType_documentOwned_0" element
        And I should see a "input#objects_objects_0_documentType_documentOwned_1" element
        And the "objects_objects_0_documentType_documentOwned_0" checkbox should be checked
        When I click the "label[for=objects_objects_0_documentType_documentOwned_1]" element
        Then I should see the key "pel.precise.owner.lastname.and.firstname" translated
        And I should see a "input#objects_objects_0_documentType_documentOwner" element
        And I should see a "select#objects_objects_0_status" element
        And I should see a "input#objects_objects_0_amount" element
        And I should not see a "select#objects_objects_0_label" element
        And I should see "Carte d'identité" in the "#objects_objects_0_documentType" element
        And I should see "Passeport" in the "#objects_objects_0_documentType" element
        And I should see "Titre de séjour" in the "#objects_objects_0_documentType" element
        And I should see "Permis de conduire " in the "#objects_objects_0_documentType" element
        And I should see "Carte grise" in the "#objects_objects_0_documentType" element
        And I should see "Carte vitale" in the "#objects_objects_0_documentType" element
        And I should see "Carte professionnelle" in the "#objects_objects_0_documentType" element
        And I should see "Autre" in the "#objects_objects_0_documentType" element
        When I select "Autre" from "objects_objects_0_documentType_documentType"
        Then I should see a "input#objects_objects_0_documentType_otherDocumentType" element
        And I should see the key "pel.could.you.precise" translated

    Scenario: I can delete an input text when I click on the delete an object button
        When I press "objects_objects_add"
        And I press "objects_objects_1_delete"
        Then I should not see a "input#objects_objects_1_label" element

    Scenario: I should see 2 inputs when I select "Other" for object category
        When I select "6" from "objects_objects_0_category"
        Then I should see the key "pel.description" translated
        And I should see the key "pel.quantity" translated
        And I should see a "input#objects_objects_0_description" element
        And I should see a "input#objects_objects_0_quantity" element
        And I should see the key "pel.amount.for.group" translated
        And I should see a "select#objects_objects_0_status" element
        And I should see the key "pel.amount.for.group" translated
        And I should see the key "pel.object.status" translated

    Scenario: I can see a list of text fields translated when I select "Véhicules immatriculés" from category object list
        When I select "Véhicules immatriculés" from "objects_objects_0_category"
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.registration.number" translated
        And I should see the key "pel.registration.number.country" translated
        And I should see the key "pel.insurance.company" translated
        And I should see the key "pel.insurance.number" translated
        And I should see the key "pel.amount" translated
        And I should see the key "pel.object.status" translated

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
        When I select "5" from "objects_objects_0_category"
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

    Scenario: Submit the objects form with 2 objects and 2 pdf attachments
        When I select "5" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I attach the file "blank.pdf" to "object-files-0" field
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "5" from "objects_objects_1_category"
        And I select "1" from "objects_objects_1_status"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I attach the file "blank.pdf" to "object-files-1" field
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"

    Scenario: Upload a file with a forbidden file extension
        And I attach the file "blank.xls" to "object-files-0" field
        Then I should see the key "pel.file.must.be.image.or.pdf" translated
