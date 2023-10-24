@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the offense facts step page

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
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
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_1]" element
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_1]" element
        And I select "2" from "facts_placeNature"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"

    Scenario: I can click on the back button
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/faits"

    Scenario: I can see the object category choice list
        And I should see "Document officiel" in the "#objects_objects_0_category" element
        And I should see "Moyens de paiement" in the "#objects_objects_0_category" element
        And I should see "Téléphone portable" in the "#objects_objects_0_category" element
        And I should see "Multimédia" in the "#objects_objects_0_category" element
        And I should see "Véhicules immatriculés " in the "#objects_objects_0_category" element
        And I should see "Véhicules non immatriculés" in the "#objects_objects_0_category" element
        And I should see "Autres" in the "#objects_objects_0_category" element

    Scenario: I can add an input text when I click on the add an object button
        When I press "objects_objects_add"
        Then I should see the key "pel.object" translated
        And I should see the key "pel.delete.object" translated
        And I should see the key "pel.object.category" translated
        And I should see the key "pel.object.status" translated
        And I should see the key "pel.amount" translated

    Scenario: I can see a list of text fields translated when I select "Téléphone portable" from category object list
        When I select "Téléphone portable" from "objects_objects_0_category"
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.mobile" translated
        And I should see the key "pel.mobile.phone.help" translated
        And I should see the key "pel.operator" translated
        And I should see the key "pel.serial.number" translated
        And I should see the key "pel.imei" translated
        And I should see the key "pel.imei.help" translated
        And I should see the key "pel.description" translated
        And I should see the key "pel.amount" translated
        And I should see the key "pel.object.status" translated

    Scenario: I can see a list of text fields translated when I select "Multimédia" from category object list
        When I select "Multimédia" from "objects_objects_0_category"
        Then I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.serial.number" translated
        And I should see the key "pel.imei" translated
        And I should see the key "pel.imei.help" translated
        And I should see the key "pel.description" translated
        And I should see the key "pel.amount" translated
        And I should see the key "pel.object.status" translated
        And I should see the key "pel.multimedia.nature" translated

    Scenario: I can see a list of text fields translated when I select "Moyens de paiement" from category object list
        When I select "Moyens de paiement" from "objects_objects_0_category"
        Then I should see a "input#objects_objects_0_bank" element
        And I should see the key "pel.organism.bank" translated
        And I should see a "input#objects_objects_0_bankAccountNumber" element
        And I should see the key "pel.bank.account.number" translated
        And I should see a "input#objects_objects_0_creditCardNumber" element
        And I should see the key "pel.credit.card.number" translated
        And I should see a "select#objects_objects_0_status" element
        And I should see the key "pel.object.status" translated
        And I should see a "select#objects_objects_0_paymentCategory" element
        And I should see the key "pel.payment.category" translated
        And I should see the key "pel.object.category.choose" translated

    Scenario: I can see a list of text fields translated when I select "Document officiel" from category object list
        When I select "Document officiel" from "objects_objects_0_category"
        Then I should see the key "pel.document.type" translated
        And I should see the key "pel.object.status" translated
        And I should see a "select#objects_objects_0_documentType_documentType" element
        And I should see a "select#objects_objects_0_status" element
        And I should not see a "select#objects_objects_0_label" element
        When I select "AUTRE NATURE DOCUMENT" from "objects_objects_0_documentType_documentType"
        Then I should see a "input#objects_objects_0_documentType_otherDocumentType" element
        And I should see the key "pel.could.you.precise" translated
        And I should see a "#objects_objects_0_documentType_documentIssuingCountry" element
        And I should see the key "pel.document.number" translated
        And I should see the key "pel.document.issued.by" translated
        And I should see the key "pel.document.issued.on" translated
        And I should see the key "pel.document.validity.end.date" translated

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
        And I should see a "input#objects_objects_0_serialNumber" element
        And I should see the key "pel.other.serial.number" translated
        And I should see the key "pel.this.number.is.required.to.identity.your.object" translated

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
        And I should see the key "pel.vehicle.category" translated

    Scenario: I should see an objects quantity / amount text when I fill an object form
        When I select "6" from "objects_objects_0_category"
        And I fill in "objects_objects_0_quantity" with "10"
        And I fill in "objects_objects_0_amount" with "99.99"
        And I press "objects_objects_add"
        And I select "6" from "objects_objects_1_category"
        And I fill in "objects_objects_1_quantity" with "10"
        And I fill in "objects_objects_1_amount" with "100"
        Then I should see "20 biens déclarés pour un montant total de 199,99 €"

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

    Scenario: I can see a list of text fields translated when I am not the owner of the administrative document
        When I select "Document officiel" from "objects_objects_0_category"
        Then I should see the key "pel.i.am.the.owner.of.this.document" translated
        And I should see a "input#objects_objects_0_documentType_documentOwned_0" element
        And I should see a "input#objects_objects_0_documentType_documentOwned_1" element
        And the "objects_objects_0_documentType_documentOwned_0" checkbox should be checked
        When I click the "label[for=objects_objects_0_documentType_documentOwned_1]" element
        And I should see the key "pel.document.owner.address" translated
        And I should see the key "pel.document.owner.email" translated
        And I should see the key "pel.document.owner.firstname" translated
        And I should see the key "pel.document.owner.lastname" translated
        And I should see the key "pel.document.owner.phone" translated

    Scenario: I can see 4 radios buttons, a text and a required checkbox when I have a mobile phone stolen
        When I select "Téléphone portable" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_imei" with "1234"
        Then I should see the key "pel.still.on.when.mobile.stolen" translated
        And I should see a "input[type=radio]#objects_objects_0_stillOnWhenMobileStolen_0" element
        And I should see a "input[type=radio]#objects_objects_0_stillOnWhenMobileStolen_1" element
        And I should see the key "pel.keyboard.locked.when.mobile.stolen" translated
        And I should see a "input[type=radio]#objects_objects_0_keyboardLockedWhenMobileStolen_0" element
        And I should see a "input[type=radio]#objects_objects_0_keyboardLockedWhenMobileStolen_1" element
        And I should see the key "pel.pin.enabled.when.mobile.stolen" translated
        And I should see a "input[type=radio]#objects_objects_0_pinEnabledWhenMobileStolen_0" element
        And I should see a "input[type=radio]#objects_objects_0_pinEnabledWhenMobileStolen_1" element
        And I should see the key "pel.mobile.insured" translated
        And I should see a "input[type=radio]#objects_objects_0_mobileInsured_0" element
        And I should see a "input[type=radio]#objects_objects_0_mobileInsured_1" element
        And I should see the key "pel.article.34" translated
        And I should see the key "pel.i.am.inform.of.article.34" translated
        And I should see a "input[type=checkbox]#objects_objects_0_allowOperatorCommunication" element

    Scenario: I should not see 4 radios buttons, a text and a required checkbox when I have not a mobile phone stolen
        When I select "Téléphone portable" from "objects_objects_0_category"
        And I select "2" from "objects_objects_0_status"
        Then I should not see the key "pel.still.on.when.mobile.stolen" translated
        And I should not see a "input[type=radio]#objects_objects_0_stillOnWhenMobileStolen_0" element
        And I should not see the key "pel.keyboard.locked.when.mobile.stolen" translated
        And I should not see a "input[type=radio]#objects_objects_0_keyboardLockedWhenMobileStolen_0" element
        And I should not see the key "pel.pin.enabled.when.mobile.stolen" translated
        And I should not see a "input[type=radio]#objects_objects_0_pinEnabledWhenMobileStolen_0" element
        And I should not see the key "pel.mobile.insured" translated
        And I should not see a "input[type=radio]#objects_objects_0_mobileInsured_0" element
        And I should not see the key "pel.article.34" translated
        And I should not see the key "pel.i.am.inform.of.article.34" translated
        And I should not see a "input[type=checkbox]#objects_objects_0_allowOperatorCommunication" element

    Scenario: I should see the degradation description field required when the vehicle is degraded
        When I select "Véhicules immatriculés" from "objects_objects_0_category"
        And I select "2" from "objects_objects_0_status"
        Then I should see a "textarea#objects_objects_0_degradationDescription[required=required]" element

    @flaky
    Scenario: I should see the degradation description field not required when the vehicle is stolen
        When I select "Véhicules immatriculés" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        Then I should not see a "textarea#objects_objects_0_degradationDescription[required=required]" element

    Scenario: I should see check fields when I select "Moyens de paiement" and "CHEQUIER"
        When I select "2" from "objects_objects_0_category"
        And I select "00001" from "objects_objects_0_paymentCategory"
        Then I should see a "input#objects_objects_0_checkNumber" element
        And I should see the key "pel.check.number" translated
        And I should see a "input#objects_objects_0_checkFirstNumber" element
        And I should see the key "pel.first.check.number" translated
        And I should see a "input#objects_objects_0_checkLastNumber" element
        And I should see the key "pel.last.check.number" translated

    Scenario: I should not see check fields when I select "Moyens de paiement" and "carte bancaire"
        When I select "2" from "objects_objects_0_category"
        And I select "00004" from "objects_objects_0_paymentCategory"
        Then I should not see a "input#objects_objects_0_checkNumber" element
        And I should not see the key "pel.check.number" translated
        And I should not see a "input#objects_objects_0_checkFirstNumber" element
        And I should not see the key "pel.first.check.number" translated
        And I should not see a "input#objects_objects_0_checkLastNumber" element
        And I should not see the key "pel.last.check.number" translated

    Scenario: I should not see check fields when I select "Véhicules immatriculés"
        When I select "4" from "objects_objects_0_category"
        Then I should not see a "input#objects_objects_0_checkNumber" element
        And I should not see the key "pel.check.number" translated
        And I should not see a "input#objects_objects_0_checkFirstNumber" element
        And I should not see the key "pel.first.check.number" translated
        And I should not see a "input#objects_objects_0_checkLastNumber" element
        And I should not see the key "pel.last.check.number" translated

    Scenario: I can click on the link "Je n’ai pas de bien à déclarer" and go to the next step
        When I follow "Je n’ai pas de bien à déclarer"
        Then I should be on "/porter-plainte/informations-complementaires/1"
