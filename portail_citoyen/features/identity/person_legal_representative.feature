Feature:
    In order to fill a complaint
    As a user
    I want to see the person legal representative declarant form

    @javascript
    Scenario: I can select the person legal representative radio button
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
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
        And I should see the key "pel.next" translated

    @javascript
    Scenario: Submit the form with minimal valid values for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait 100 ms
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
        And I wait and select "1" from "identity_representedPersonCivilState_civility"
        And I wait and fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I wait and fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_representedPersonCivilState_nationality"
        And I wait and select "1" from "identity_representedPersonCivilState_job"
        And I wait and select "Paris (75)" from "identity_representedPersonContactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_representedPersonContactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_representedPersonContactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_representedPersonContactInformation_frenchAddressWay"
        And I wait and fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with birthCountry is France for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "1" from "identity_civilState_birthLocation_country"
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
        And I wait and select "1" from "identity_representedPersonCivilState_civility"
        And I wait and fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I wait and fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_representedPersonCivilState_nationality"
        And I wait and select "1" from "identity_representedPersonCivilState_job"
        And I wait and select "Paris (75)" from "identity_representedPersonContactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_representedPersonContactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_representedPersonContactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_representedPersonContactInformation_frenchAddressWay"
        And I wait and fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with another birthCountry than France for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "2" from "identity_civilState_birthLocation_country"
        And I wait for the element "identity_civilState_birthLocation_otherTown" to appear
        And I wait and fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_contactInformation_mobile" with "0602030405"
        And I wait and select "1" from "identity_representedPersonCivilState_civility"
        And I wait and fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I wait and fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_representedPersonCivilState_nationality"
        And I wait and select "1" from "identity_representedPersonCivilState_job"
        And I wait and select "Paris (75)" from "identity_representedPersonContactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_representedPersonContactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_representedPersonContactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_representedPersonContactInformation_frenchAddressWay"
        And I wait and fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with addressCountry is France for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait 100 ms
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "1" from "identity_contactInformation_addressLocation_country"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_frenchAddressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_contactInformation_mobile" with "0602030405"
        And I wait and select "1" from "identity_representedPersonCivilState_civility"
        And I wait and fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I wait and fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_representedPersonCivilState_nationality"
        And I wait and select "1" from "identity_representedPersonCivilState_job"
        And I wait and select "Paris (75)" from "identity_representedPersonContactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_representedPersonContactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_representedPersonContactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_representedPersonContactInformation_frenchAddressWay"
        And I wait and fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with another addressCountry than France for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "1" from "identity_civilState_birthLocation_country"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "2" from "identity_contactInformation_addressLocation_country"
        And I wait for the element "identity_contactInformation_addressLocation_otherTown" to appear
        And I wait and fill in "identity_contactInformation_addressLocation_otherTown" with "Madrid"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and fill in "identity_contactInformation_foreignAddressWay" with "way"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_contactInformation_mobile" with "0602030405"
        And I wait and select "1" from "identity_representedPersonCivilState_civility"
        And I wait and fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I wait and fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_representedPersonCivilState_nationality"
        And I wait and select "1" from "identity_representedPersonCivilState_job"
        And I wait and select "Paris (75)" from "identity_representedPersonContactInformation_addressLocation_frenchTown"
        And I wait for the "#identity_representedPersonContactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_representedPersonContactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_representedPersonContactInformation_frenchAddressWay"
        And I wait and fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form without any required values
        Given I am on "/identite"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 1 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 2 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 3 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 4 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 5 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 6 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
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
    Scenario: Submit the form with only 7 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 8 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 9 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
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
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 10 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
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
    Scenario: Submit the form with only 11 required value for person legal representative declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
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
    Scenario: Select the Person Legal Representative and see "Sans Profession" in the job field
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        Then I should see "Sans Profession" in the "#identity_representedPersonCivilState_job" element

    @javascript
    Scenario: Submit the form with an invalid birth date (under 18) for declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2020"
        And I wait 100 ms
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_town"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_town"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_addressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_contactInformation_mobile" with "0602030405"
        And I wait and select "1" from "identity_representedPersonCivilState_civility"
        And I wait and fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I wait and fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_town"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_representedPersonCivilState_nationality"
        And I wait and select "1" from "identity_representedPersonCivilState_job"
        And I wait and select "Paris (75)" from "identity_representedPersonContactInformation_addressLocation_town"
        And I wait for the "#identity_representedPersonContactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_representedPersonContactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_representedPersonContactInformation_addressWay"
        And I wait and fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with an invalid birth date (over 120) for declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        And I wait and select "1" from "identity_civilState_civility"
        And I wait and fill in "identity_civilState_birthName" with "Dupont"
        And I wait and fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_civilState_birthDate" with "01/01/2000"
        And I wait 100 ms
        And I wait and select "Paris (75)" from "identity_civilState_birthLocation_town"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_civilState_nationality"
        And I wait and select "1" from "identity_civilState_job"
        And I wait and select "Paris (75)" from "identity_contactInformation_addressLocation_town"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_contactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_contactInformation_addressWay"
        And I wait and fill in "identity_contactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_contactInformation_mobile" with "0602030405"
        And I wait and select "1" from "identity_representedPersonCivilState_civility"
        And I wait and fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I wait and fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I wait and fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I wait and select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_town"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I wait and select "1" from "identity_representedPersonCivilState_nationality"
        And I wait and select "1" from "identity_representedPersonCivilState_job"
        And I wait and select "Paris (75)" from "identity_representedPersonContactInformation_addressLocation_town"
        And I wait for the "#identity_representedPersonContactInformation_addressLocation_department" field to contain "75"
        And I wait and fill in "identity_representedPersonContactInformation_addressNumber" with "01"
        And I wait and select "1" from "identity_representedPersonContactInformation_addressWay"
        And I wait and fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I wait and fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/identite"
