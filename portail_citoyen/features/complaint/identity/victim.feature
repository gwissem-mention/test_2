@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the victim declarant form

    Background:
        Given I am on "/authentification"
        And I press "no_france_connect_auth_button"
        And I follow "no_france_connect_auth_button_confirm"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"
        And I click the "label[for=identity_declarantStatus_0]" element

    Scenario: I can see the fields for Victim
        Then I should see the key "pel.birth.name" translated
        And I should see the key "pel.usage.name" translated
        And I should see the key "pel.first.names" translated
        And I should see the key "pel.family.situation" translated
        And I should see the key "pel.birth.date" translated
        And I should see the key "pel.birth.country" translated
        And I should see the key "pel.birth.town" translated
        And I should see the key "pel.nationality" translated
        And I should see the key "pel.your.job" translated
        And I should see the key "pel.address.country" translated
        And I should see the key "pel.address" translated
        And I should see the key "pel.email.address" translated
        And I should see the key "pel.mobile" translated
        And I should see the key "pel.mobile.phone.help" translated
        And I should see the key "pel.phone" translated
        And I should see the key "pel.phone.help" translated
        And I should see the key "pel.judicial.portal.text" translated
        And I should see the key "pel.by.email" translated
        And I should see the key "pel.by.sms" translated
        And I should see the key "pel.next" translated

    Scenario: Change country from France to Spain and check the town field is cleared
        When I select "99160" from "identity_civilState_birthLocation_country"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I should see "Paris (75000)" in the "#identity_civilState_birthLocation_frenchTown" element
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I should not see a "identity_civilState_birthLocation_frenchTown" element
        And I should not see "Paris (75000)" in the "#identity_civilState_birthLocation_otherTown" element

    Scenario: Submit the form with minimal valid values for victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I click the "label[for=identity_consentContactEmail]" element
        And I click the "label[for=identity_consentContactSMS]" element
        And I click the "label[for=identity_consentContactPortal]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with another birthCountry than France for victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with no phones filled
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_contactInformation_mobile_number" element
        And I should see a "#form-errors-identity_contactInformation_phone_number" element

    Scenario: Submit the form with another addressCountry than France for victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress_housenumber" with "14"
        And I fill in "identity_contactInformation_foreignAddress_type" with "Corto"
        And I fill in "identity_contactInformation_foreignAddress_street" with "de Alcalá"
        And I fill in "identity_contactInformation_foreignAddress_apartment" with "2"
        And I fill in "identity_contactInformation_foreignAddress_city" with "Madrid"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form without any required values
        When I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_civility" element

    Scenario: Submit the form with only 1 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthName" element

    Scenario: Submit the form with only 2 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_firstnames" element

    Scenario: Submit the form with only 3 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_familySituation" element

    Scenario: Submit the form with only 4 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthDate" element

    Scenario: Submit the form with only 5 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthLocation_frenchTown" element

    Scenario: Submit the form with only 6 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_job" element

    Scenario: Submit the form with only 7 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-contact-information-address" element
#        And the field "contact-information-address" should have focus

    Scenario: Submit the form with only 8 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_contactInformation_email" element

    Scenario: Submit the form with only 9 required value for Victim declarant
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_contactInformation_phone_number" element

    Scenario: I fill the identity form as france connected, when I go back, the identity data should be saved
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I check "law_refresher_lawRefresherAccepted"
        And I press "law_refresher_submit"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_familySituation"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I am on "/porter-plainte/identite"
        Then the "identity_civilState_birthName" field should contain "Dupont"
        And the "identity_civilState_firstnames" field should contain "Michel"
        And the "identity_civilState_familySituation" field should contain "1"
        And the "identity_civilState_birthDate" field should contain "1967-03-02"
        And the "identity_civilState_birthLocation_frenchTown" field should contain "75107"
        And the "identity_civilState_job" field should contain "abatteur_de_bestiaux"
        And the "contact-information-address" field should contain "Avenue de la République 33000 Bordeaux"
        And the "identity_contactInformation_email" field should contain "michel.dupont@example.com"
        And the "identity_contactInformation_phone_number" field should contain "1 02 03 04 05"

    Scenario: I fill the identity form as not france connected, when go back, the identity data should be saved
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        When I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I am on "/porter-plainte/identite"
        Then the "identity_civilState_birthName" field should contain "Dupont"
        And the "identity_civilState_firstnames" field should contain "Jean Pierre Marie"
        And the "identity_civilState_familySituation" field should contain "1"
        And the "identity_civilState_birthDate" field should contain "2000-01-01"
        And the "identity_civilState_birthLocation_frenchTown" field should contain "75056"
        And the "identity_civilState_job" field should contain "abatteur_de_bestiaux"
        And the "contact-information-address" field should contain "Avenue de la République 33000 Bordeaux"
        And the "identity_contactInformation_email" field should contain "jean@test.com"
        And the "identity_contactInformation_phone_number" field should contain "1 02 03 04 05"

    Scenario: Submit the form, selecting birth country France, then Spain, then France again
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I select "99160" from "identity_civilState_birthLocation_country"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form without selecting a etalab address
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see "Veuillez sélectionner une des adresses proposées sous le champ pour continuer."

    Scenario: Submit the form selecting a address not in Gironde department
        When I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "Avenue de la République Paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see "Uniquement les adresses en Gironde sont acceptées"
