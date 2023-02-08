@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the corporation legal representative declarant form

    Background:
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"

    Scenario: I can select the Corporation Legal Representative radio button
        When I click the "label[for=identity_declarantStatus_2]" element
        Then I should see the key "pel.all.fields.are.required" translated
        And I should see the key "pel.civility" translated
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
        When I click the "label[for=identity_declarantStatus_2]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone_number" with "0601020304"
        And I fill in "identity_corporation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    #Scenario: Submit the form with the same address as the declarant checkbox
        #When I click the "label[for=identity_declarantStatus_2]" element
        #And I select "1" from "identity_civilState_civility"
        #And I fill in "identity_civilState_birthName" with "Dupont"
        #And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        #And I fill in "identity_civilState_birthDate" with "01/01/2000"
        #And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        #And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        #And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        #And I fill in "identity_contactInformation_email" with "jean@test.com"
        #And I fill in "identity_contactInformation_phone_number" with "0601020304"
        #And I fill in "identity_corporation_siren" with "123456789"
        #And I fill in "identity_corporation_name" with "Test Company"
        #And I fill in "identity_corporation_function" with "Developer"
        #And I fill in "identity_corporation_email" with "jean@test.com"
        #And I fill in "identity_corporation_phone_number" with "0601020304"
        #And I click the "label[for=identity_corporation_sameAddress]" element
        #Then the "identity[corporation][frenchAddress][address]" field should contain "Av. de la République 75011 Paris France"
        #And I press "identity_submit"
        #Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form without any required values
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_civility" should have focus

    Scenario: Submit the form with only 1 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthName" should have focus

    Scenario: Submit the form with only 2 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_firstnames" should have focus

    Scenario: Submit the form with only 3 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthDate" should have focus

    Scenario: Submit the form with only 4 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthLocation_frenchTown" should have focus

    Scenario: Submit the form with only 5 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_job" should have focus

    Scenario: Submit the form with only 6 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_contactInformation_frenchAddress_address" should have focus

    Scenario: Submit the form with only 7 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_contactInformation_email" should have focus

    Scenario: Submit the form with only 8 required value for Corporation Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_contactInformation_phone_number" should have focus

    Scenario: Submit the form with only 10 required value for Corporation Legal Representative declarant
        When I click the "label[for=identity_declarantStatus_2]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_corporation_siren" should have focus

    Scenario: Submit the form with only 11 required value for Corporation Legal Representative declarant
        When I click the "label[for=identity_declarantStatus_2]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_corporation_name" should have focus

    Scenario: Submit the form with only 12 required value for Corporation Legal Representative declarant
        When I click the "label[for=identity_declarantStatus_2]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_corporation_function" should have focus

    Scenario: Submit the form with only 13 required value for Corporation Legal Representative declarant
        When I click the "label[for=identity_declarantStatus_2]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_corporation_phone_number" should have focus

    Scenario: Submit the form with only 14 required value for Corporation Legal Representative declarant
        When I click the "label[for=identity_declarantStatus_2]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_phone_number" with "0601020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_corporation_frenchAddress_address" should have focus

    Scenario: Submit the form with invalid siren (too short) for Corporation Legal Representative declarant
        When I click the "label[for=identity_declarantStatus_2]" element
        And I fill in "identity_corporation_siren" with "1"
        Then I should see a "#form-errors-identity_corporation_siren" element
        And I should see "Cette chaîne doit avoir exactement 9 caractères." in the "#form-errors-identity_corporation_siren" element

    Scenario: Submit the form with invalid siren (letters) for Corporation Legal Representative declarant
        When I click the "label[for=identity_declarantStatus_2]" element
        And I fill in "identity_corporation_siren" with "ABCDEFGHI"
        Then I should see a "#form-errors-identity_corporation_siren" element
        And I should see "Seuls les chiffres sont autorisés." in the "#form-errors-identity_corporation_siren" element
