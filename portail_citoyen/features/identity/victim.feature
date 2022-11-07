Feature:
    In order to fill a complaint
    As a user
    I want to see the victim declarant form

    @javascript
    Scenario: I can select the Victim radio button
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
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
        And I should see the key "pel.email" translated
        And I should see the key "pel.mobile" translated
        And I should see the key "pel.next" translated

    @javascript
    Scenario: Change country from France to Spain and check the town field is cleared
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "FR" from "identity_civilState_birthLocation_country"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I should see "Paris" in the "#identity_civilState_birthLocation_frenchTown" element
        And I select "ES" from "identity_civilState_birthLocation_country"
        And I wait for the element "#identity_civilState_birthLocation_otherTown" to appear
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I should not see a "identity_civilState_birthLocation_frenchTown" element
        And I should not see "Paris" in the "#identity_civilState_birthLocation_otherTown" element

    @javascript
    Scenario: Submit the form with minimal valid values for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with birthCountry is France for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "FR" from "identity_civilState_birthLocation_country"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with another birthCountry than France for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "ES" from "identity_civilState_birthLocation_country"
        And I wait for the element "identity_civilState_birthLocation_otherTown" to appear
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with addressCountry is France for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I select "FR" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form with another addressCountry than France for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "FR" from "identity_civilState_birthLocation_country"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I select "ES" from "identity_contactInformation_country"
        And I wait for the element "#identity_contactInformation_foreignAddress" to appear
        And I fill in "identity_contactInformation_foreignAddress" with "C. de Alcalá Madrid España"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am redirected on "/faits"

    @javascript
    Scenario: Submit the form without any required values
        Given I am on "/identite"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 1 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 2 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 3 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 4 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 5 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 6 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 7 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 8 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the form with only 9 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I press "Suivant"
        Then I am redirected on "/identite"


    @javascript
    Scenario: Submit the form with only 10 required value for victim declarant
        Given I am on "/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I press "Suivant"
        Then I am redirected on "/identite"

    @javascript
    Scenario: Submit the identity form as a victim without being france connected
        Given I am on "/authentification"
        When I follow "Continuer sans m'authentifier"
        Then I am on "/identite?france_connected=0"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0601020304"
        And I press "Suivant"
        Then I am on "/faits"

    @javascript
    Scenario: Submit the identity form as a victim being france connected
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I am on "/identite?france_connected=1"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I wait for the element "#form-identity" to appear
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_mobile" with "0601020304"
        And I press "Suivant"
        Then I am on "/faits"
