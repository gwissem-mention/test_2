@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the person legal representative declarant form

    Background:
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I follow "Je confirme"

    Scenario: I can select the person legal representative radio button
        When I click the "label[for=identity_declarantStatus_1]" element
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
        And I should see the key "pel.phone" translated
        And I should see the key "pel.next" translated

    Scenario: Submit the form with minimal valid values for person legal representative declarant
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "represented-person-address" with "avenue de la république paris"
        And I click the "#represented-person-address-75111_8158" element
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102030405"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with the same address as the declarant checkbox with french address
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I click the "label[for=identity_representedPersonContactInformation_sameAddress]" element
        Then the "represented-person-address" field should contain "Avenue de la République 75011 Paris"
        When I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102030405"
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
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "represented-person-address" with "avenue de la république paris"
        And I click the "#represented-person-address-75111_8158" element
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102030405"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with another addressCountry than France for person legal representative declarant
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
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
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "represented-person-address" with "avenue de la république paris"
        And I click the "#represented-person-address-75111_8158" element
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102030405"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with the same address as the declarant checkbox with foreign address
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
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
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I select "99134" from "identity_representedPersonContactInformation_country"
        And I click the "label[for=identity_representedPersonContactInformation_sameAddress]" element
        Then the "identity_representedPersonContactInformation_foreignAddress_housenumber" field should contain "14"
        Then the "identity_representedPersonContactInformation_foreignAddress_type" field should contain "Corto"
        Then the "identity_representedPersonContactInformation_foreignAddress_street" field should contain "de Alcalá Madrid España"
        When I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102030405"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with no phones filled for represented person
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "represented-person-address" with "avenue de la république paris"
        And I click the "#represented-person-address-75111_8158" element
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_representedPersonContactInformation_mobile_number" element
        And I should see a "#form-errors-identity_representedPersonContactInformation_phone_number" element

    Scenario: Submit the form without any required values
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_civility" element

    Scenario: Submit the form with only 1 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthName" element

    Scenario: Submit the form with only 2 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_firstnames" element

    Scenario: Submit the form with only 3 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthDate" element

    Scenario: Submit the form with only 4 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthLocation_frenchTown" element

    Scenario: Submit the form with only 5 required value for Person Legal Representative declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_job" element

    Scenario: Submit the form with only 6 required value for Person Legal Representative declarant
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

    Scenario: Submit the form with only 7 required value for Person Legal Representative declarant
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

    Scenario: Submit the form with only 8 required value for Person Legal Representative declarant
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
