@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the corporation legal representative declarant form

    Background:
        Given I am on "/authentification"
        And I follow "no_france_connect_auth_button"
        And I follow "Je confirme"
        Then I should be on "/porter-plainte/statut-declarant"
        And I click the "label[for=declarant_status_declarantStatus_2]" element
        And I press "declarant_status_submit"
        And I am on "/porter-plainte/identite"

    Scenario: I can select the Corporation Legal Representative radio button
        Then I should see the key "pel.civility" translated
        And I should see the key "pel.birth.name" translated
        And I should see the key "pel.usage.name" translated
        And I should see the key "pel.first.names" translated
        And I should see the key "pel.birth.date" translated
        And I should see the key "pel.birth.country" translated
        And I should see the key "pel.birth.town" translated
        And I should see the key "pel.birth.department" translated
        And I should see the key "pel.nationality" translated
        And I should see the key "pel.your.job" translated
        And I should see the key "pel.address.country" translated
        And I should see the key "pel.address" translated
        And I should see the key "pel.same.address.as.declarant" translated
        And I should see the key "pel.email" translated
        And I should see the key "pel.mobile" translated
        And I should see the key "pel.corporation.siren" translated
        And I should see the key "pel.corporation.name" translated
        And I should see the key "pel.corporation.function" translated
        And I should see the key "pel.nationality" translated
        And I should see the key "pel.corporation.email" translated
        And I should see the key "pel.corporation.phone" translated
        And I should see the key "pel.next" translated

    Scenario: Submit the form with minimal valid values for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone_number" with "0102020304"
        And I fill in "corporation-address" with "avenue de la république paris"
        And I click the "#corporation-address-38485_0570" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with the same address as the declarant checkbox
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone_number" with "0102020304"
        And I click the "label[for=identity_corporation_sameAddress]" element
        Then the "corporation-address" field should contain "Avenue de la République 75011 Paris"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form without any required values
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_civility" element

    Scenario: Submit the form with only 1 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthName" element

    Scenario: Submit the form with only 2 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_firstnames" element

    Scenario: Submit the form with only 3 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthDate" element

    Scenario: Submit the form with only 4 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthLocation_frenchTown" element

    Scenario: Submit the form with only 5 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_job" element

    Scenario: Submit the form with only 6 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-contact-information-address" element
#        And the field "contact-information-address" should have focus

    Scenario: Submit the form with only 7 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_contactInformation_email" element

    Scenario: Submit the form with only 8 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_contactInformation_phone_number" element

    Scenario: Submit the form with only 10 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_corporation_siren" element

    Scenario: Submit the form with only 11 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_corporation_name" element

    Scenario: Submit the form with only 12 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_corporation_function" element

    Scenario: Submit the form with only 13 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_corporation_phone_number" element

    Scenario: Submit the form with only 14 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#contact-information-address" element
#        And the field "corporation-address" should have focus

    Scenario: Submit the form with invalid siren (too short) for Corporation Legal Representative declarant
        When I fill in "identity_corporation_siren" with "1"
        Then I should see a "#form-errors-identity_corporation_siren" element
        And I should see "Cette chaîne doit avoir exactement 9 caractères." in the "#form-errors-identity_corporation_siren" element

    Scenario: Submit the form with invalid siren (letters) for Corporation Legal Representative declarant
        When I fill in "identity_corporation_siren" with "ABCDEFGHI"
        Then I should see a "#form-errors-identity_corporation_siren" element
        And I should see "Seuls les chiffres sont autorisés." in the "#form-errors-identity_corporation_siren" element

    Scenario: Submit the form with another addressCountry than France
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress_housenumber" with "14"
        And I fill in "identity_contactInformation_foreignAddress_type" with "Corto"
        And I fill in "identity_contactInformation_foreignAddress_street" with "de Alcalá Madrid España"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone_number" with "0102020304"
        And I select "99134" from "identity_corporation_country"
        And I fill in "identity_corporation_foreignAddress_housenumber" with "14"
        And I fill in "identity_corporation_foreignAddress_type" with "Corto"
        And I fill in "identity_corporation_foreignAddress_street" with "de Alcalá Madrid España"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with another addressCountry than France and same address as declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress_housenumber" with "14"
        And I fill in "identity_contactInformation_foreignAddress_type" with "Corto"
        And I fill in "identity_contactInformation_foreignAddress_street" with "de Alcalá Madrid España"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone_number" with "0102020304"
        And I select "99134" from "identity_corporation_country"
        And I click the "label[for=identity_corporation_sameAddress]" element
        Then the "identity_corporation_foreignAddress_housenumber" field should contain "14"
        Then the "identity_corporation_foreignAddress_type" field should contain "Corto"
        Then the "identity_corporation_foreignAddress_street" field should contain "de Alcalá Madrid España"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
