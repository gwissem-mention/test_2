# Person Legal Representative must be hidden for the experimentation

#@javascript
#Feature:
#    In order to fill a complaint
#    As a user
#    I want to see the person legal representative declarant form
#
#    Background:
#        Given I am on "/authentification"
#        And I press "no_france_connect_auth_button"
#        And I follow "no_france_connect_auth_button_confirm"
#        Then I should be on "/porter-plainte/rappel-a-la-loi"
#        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
#        And I press "law_refresher_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I click the "label[for=identity_declarantStatus_1]" element
#
#    Scenario: I can select the person legal representative radio button
#        Then I should see the key "pel.birth.name" translated
#        And I should see the key "pel.usage.name" translated
#        And I should see the key "pel.first.names" translated
#        And I should see the key "pel.family.situation" translated
#        And I should see the key "pel.birth.date" translated
#        And I should see the key "pel.birth.country" translated
#        And I should see the key "pel.birth.town" translated
#        And I should see the key "pel.nationality" translated
#        And I should see the key "pel.your.job" translated
#        And I should see the key "pel.address.country" translated
#        And I should see the key "pel.address" translated
#        And I should see the key "pel.same.address.as.declarant" translated
#        And I should see the key "pel.email.address" translated
#        And I should see the key "pel.mobile" translated
#        And I should see the key "pel.phone" translated
#        And I should see the key "pel.next" translated
#
#    Scenario: Submit the form with minimal valid values for person legal representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "represented-person-address" with "avenue de la république bordeaux"
#        And I click the "#represented-person-address-33063_8132" element
#        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/faits"
#
#    Scenario: Submit the form with the same address as the declarant checkbox with french address
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I click the "label[for=identity_representedPersonContactInformation_sameAddress]" element
#        Then the "represented-person-address" field should contain "Avenue de la République 33000 Bordeaux"
#        When I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/faits"
#
#    Scenario: Submit the form with another birthCountry than France for person legal representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I select "99134" from "identity_civilState_birthLocation_country"
#        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "represented-person-address" with "avenue de la république bordeaux"
#        And I click the "#represented-person-address-33063_8132" element
#        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/faits"
#
#    Scenario: Submit the form with another addressCountry than France for person legal representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I select "99134" from "identity_contactInformation_country"
#        And I fill in "identity_contactInformation_foreignAddress_housenumber" with "14"
#        And I fill in "identity_contactInformation_foreignAddress_type" with "Corto"
#        And I fill in "identity_contactInformation_foreignAddress_street" with "de Alcalá Madrid España"
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "represented-person-address" with "avenue de la république bordeaux"
#        And I click the "#represented-person-address-33063_8132" element
#        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/faits"
#
#    Scenario: Submit the form with the same address as the declarant checkbox with foreign address
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I select "99134" from "identity_contactInformation_country"
#        And I fill in "identity_contactInformation_foreignAddress_housenumber" with "14"
#        And I fill in "identity_contactInformation_foreignAddress_type" with "Corto"
#        And I fill in "identity_contactInformation_foreignAddress_street" with "de Alcalá Madrid España"
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I select "99134" from "identity_representedPersonContactInformation_country"
#        And I wait 500 ms
#        And I click the "label[for=identity_representedPersonContactInformation_sameAddress]" element
#        Then the "identity_representedPersonContactInformation_foreignAddress_housenumber" field should contain "14"
#        And the "identity_representedPersonContactInformation_foreignAddress_type" field should contain "Corto"
#        And the "identity_representedPersonContactInformation_foreignAddress_street" field should contain "de Alcalá Madrid España"
#        When I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        When I fill in "identity_representedPersonContactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/faits"
#
#    Scenario: Submit the form with no phones filled for represented person
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "represented-person-address" with "avenue de la république bordeaux"
#        And I click the "#represented-person-address-33063_8132" element
#        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_confirmationEmail" with "jean@test.com"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonContactInformation_mobile_number" element
#        And I should see a "#form-errors-identity_representedPersonContactInformation_phone_number" element
#
#    Scenario: Submit the form without any required values
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_civilState_civility" element
#
#    Scenario: Submit the form with only 1 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_civilState_birthName" element
#
#    Scenario: Submit the form with only 2 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_civilState_firstnames" element
#
#    Scenario: Submit the form with only 3 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_civilState_familySituation" element
#
#    Scenario: Submit the form with only 4 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_civilState_birthDate" element
#
#    Scenario: Submit the form with only 5 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_civilState_birthLocation_frenchTown" element
#
#    Scenario: Submit the form with only 6 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_civilState_job" element
#
#    Scenario: Submit the form with only 7 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-contact-information-address" element
##        And the field "contact-information-address" should have focus
#
#    Scenario: Submit the form with only 8 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_contactInformation_email" element
#
#    Scenario: Submit the form with only 9 required value for Person Legal Representative declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_contactInformation_phone_number" element
#
#    Scenario: Submit the form with only 10 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonCivilState_civility" element
#
#    Scenario: Submit the form with only 11 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonCivilState_birthName" element
#
#    Scenario: Submit the form with only 12 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonCivilState_firstnames" element
#
#    Scenario: Submit the form with only 13 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonCivilState_familySituation" element
#
#    Scenario: Submit the form with only 14 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonCivilState_birthDate" element
#
#    Scenario: Submit the form with only 15 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonCivilState_birthLocation_frenchTown" element
#
#    Scenario: Submit the form with only 16 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonCivilState_job" element
#
#    Scenario: Submit the form with only 17 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-represented-person-address" element
#
#    Scenario: Submit the form with only 18 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "represented-person-address" with "avenue de la république bordeaux"
#        And I click the "#represented-person-address-33063_8132" element
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonContactInformation_email" element
#
#    Scenario: Submit the form with only 19 required values for Person Legal Representative Declarant
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I fill in "identity_civilState_birthName" with "Dupont"
#        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in "identity_civilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_email" with "jean@test.com"
#        And I fill in "identity_contactInformation_phone_number" with "0102020304"
#        And I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "represented-person-address" with "avenue de la république bordeaux"
#        And I click the "#represented-person-address-33063_8132" element
#        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see a "#form-errors-identity_representedPersonContactInformation_mobile_number" element
#
#    Scenario: Submit the form with an invalid birth date (under 18) for declarant
#        When I fill in "identity_civilState_birthDate" with "01/01/2020"
#        And I press "identity_submit"
#        Then I should see "Vous devez avoir plus de 18 ans"
#
#    Scenario: Submit the form with an invalid birth date (over 120) for declarant
#        When I fill in "identity_civilState_birthDate" with "01/01/1900"
#        And I press "identity_submit"
#        Then I should see "Vous devez avoir moins de 120 ans"
