@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the person legal representative declarant form

    Background:
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"

    Scenario: I can select the person legal representative radio button
        When I click the "label[for=identity_declarantStatus_1]" element
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
        And I should see the key "pel.next" translated

    Scenario: Submit the form with minimal valid values for person legal representative declarant
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_representedPersonContactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with the same address as the declarant checkbox
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Avocats" and click "31B1"
        And I click the "label[for=identity_representedPersonContactInformation_sameAddress]" element
        Then the "identity_representedPersonContactInformation_frenchAddress_address" field should contain "Av. de la République 75011 Paris France"
        When I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with another birthCountry than France for person legal representative declarant
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_representedPersonContactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with another addressCountry than France for person legal representative declarant
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress" with "C. de Alcalá Madrid España"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_representedPersonContactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form without any required values
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_civility" should have focus

    Scenario: Submit the form with only 1 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthName" should have focus

    Scenario: Submit the form with only 2 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_firstnames" should have focus

    Scenario: Submit the form with only 3 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthDate" should have focus

    Scenario: Submit the form with only 4 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthLocation_frenchTown" should have focus

    Scenario: Submit the form with only 5 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_job" should have focus

    Scenario: Submit the form with only 6 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_contactInformation_frenchAddress_address" should have focus

    Scenario: Submit the form with only 7 required value for Person Legal Representative declarant
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

    Scenario: Submit the form with only 8 required value for Person Legal Representative declarant
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

    Scenario: Submit the form with an invalid birth date (under 18) for declarant
        When I click the "label[for=identity_declarantStatus_1]" element
        And I fill in "identity_civilState_birthDate" with "01/01/2020"
        And I press "identity_submit"
        Then I should see "Vous devez avoir plus de 18 ans"

    Scenario: Submit the form with an invalid birth date (over 120) for declarant
        When I click the "label[for=identity_declarantStatus_1]" element
        And I fill in "identity_civilState_birthDate" with "01/01/1900"
        And I press "identity_submit"
        Then I should see "Vous devez avoir moins de 120 ans"
