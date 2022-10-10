Feature:
    In order to fill a complaint
    As a user
    I want to see the corporation legal representative declarant form

    @javascript
    Scenario: I can select the Corporation Legal Representative radio button
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        Then I should see the key "pel.all.fields.are.required" translated
        And I should see the key "pel.civility" translated
        And I should see the key "pel.birth.name" translated
        And I should see the key "pel.first.names" translated
        And I should see the key "pel.birth.date" translated
        And I should see the key "pel.birth.country" translated
        And I should see the key "pel.birth.town" translated
        And I should see the key "pel.birth.department" translated
        And I should see the key "pel.nationality" translated
        And I should see the key "pel.your.job" translated
        And I should see the key "pel.address.country" translated
        And I should see the key "pel.address.town" translated
        And I should see the key "pel.address.department" translated
        And I should see the key "pel.address.way" translated
        And I should see the key "pel.address.number" translated
        And I should see the key "pel.email" translated
        And I should see the key "pel.mobile" translated
        And I should see the key "pel.corporation.siren" translated
        And I should see the key "pel.corporation.name" translated
        And I should see the key "pel.corporation.function" translated
        And I should see the key "pel.corporation.nationality" translated
        And I should see the key "pel.corporation.email" translated
        And I should see the key "pel.corporation.phone" translated
        And I should see the key "pel.corporation.address.country" translated
        And I should see the key "pel.corporation.address.number" translated
        And I should see the key "pel.corporation.address.way" translated
        And I should see the key "pel.corporation.address.town" translated
        And I should see the key "pel.corporation.address.department" translated
        And I should see the key "pel.next" translated


    @javascript
    Scenario: Submit the form with minimal valid values for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_contactInformation_mobile" with "0602030405"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I wait and select "1" from "identity_corporation_nationality"
        And I wait and fill in "identity_corporation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_phone" with "0602030405"
        And I wait and select "Paris (75)" from "identity_corporation_addressLocation_frenchTown"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_corporation_addressNumber" with "01"
        And I wait and select "1" from "identity_corporation_frenchAddressWay"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with no required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 1 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 2 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 3 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 4 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 5 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 6 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 7 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 8 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 9 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 10 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 11 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 12 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I wait and select "1" from "identity_corporation_nationality"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 13 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I wait and fill in "identity_corporation_phone" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 14 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I wait and select "1" from "identity_corporation_nationality"
        And I wait and fill in "identity_corporation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_phone" with "0602030405"
        And I wait and select "Paris (75)" from "identity_corporation_addressLocation_frenchTown"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 15 required value for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "123456789"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I wait and select "1" from "identity_corporation_nationality"
        And I wait and fill in "identity_corporation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_phone" with "0602030405"
        And I wait and select "Paris (75)" from "identity_corporation_addressLocation_frenchTown"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I wait and select "1" from "identity_corporation_frenchAddressWay"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with invalid siren (too short) for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "1"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I wait and select "1" from "identity_corporation_nationality"
        And I wait and fill in "identity_corporation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_phone" with "0602030405"
        And I wait and select "Paris (75)" from "identity_corporation_addressLocation_frenchTown"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I wait and select "1" from "identity_corporation_frenchAddressWay"
        And I wait and fill in "identity_corporation_addressNumber" with "01"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with invalid siren (letters) for Corporation Legal Representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_2]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_siren" with "ABCDEFGHI"
        And I wait and fill in "identity_corporation_name" with "Test Company"
        And I wait and fill in "identity_corporation_function" with "Developer"
        And I wait and select "1" from "identity_corporation_nationality"
        And I wait and fill in "identity_corporation_email" with "jean@test.com"
        And I wait and fill in "identity_corporation_phone" with "0602030405"
        And I wait and select "Paris (75)" from "identity_corporation_addressLocation_frenchTown"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I wait and select "1" from "identity_corporation_frenchAddressWay"
        And I wait and fill in "identity_corporation_addressNumber" with "01"
        And I press "Suivant"
        Then I am redirected on "/identite"

