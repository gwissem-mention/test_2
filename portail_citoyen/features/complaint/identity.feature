Feature:
    In order to fill a complaint
    As a user
    I want to see the identity step page

    @func
    Scenario: I can see the identity breadcrumb
        Given I am on "/declaration/identite"
        Then I should see "Identité" in the ".fr-breadcrumb__list" element

    @func
    Scenario: I can see a the declarant status label
        Given I am on "/declaration/identite"
        Then I should see the key "complaint.identity.declarant.status" translated

    @func
    Scenario Outline: I can see the declarant status inputs
        Given I am on "/declaration/identite"
        Then I should see 3 "input[type=radio][name='identity[declarantStatus]']" elements
        And I should see "<declarant_status>" in the "<element>" element

        Examples:
            | element                               | declarant_status                           |
            | label[for=identity_declarantStatus_0] | Victime                                    |
            | label[for=identity_declarantStatus_1] | Représentant légal d'une personne physique |
            | label[for=identity_declarantStatus_2] | Représentant légal d'une personne morale   |

    @javascript
    Scenario: I can select the Corporation Legal Representative radio button
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        Then I should see the key "all.fields.are.required" translated
        And I should see the key "siren" translated
        And I should see the key "corporation.name" translated
        And I should see the key "corporation.function" translated
        And I should see the key "nationality" translated
        And I should see the key "corporation.email" translated
        And I should see the key "corporation.phone" translated
        And I should see the key "address.country" translated
        And I should see the key "address.number" translated
        And I should see the key "address.way" translated
        And I should see the key "address.town" translated
        And I should see the key "address.department" translated
        And I should see the key "next" translated

    @javascript
    Scenario: Submit the form with minimal valid values for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        # Civil state
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_town"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        # Contact information
        And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I fill in "identity_contactInformation_addressNumber" with "01"
        And I select "1" from "identity_contactInformation_addressWay"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        # Corporation legal representative
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone" with "0602030405"
        And I select "Paris (75)" from "identity_corporation_addressLocation_town"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I select "1" from "identity_corporation_addressWay"
        And I fill in "identity_corporation_addressNumber" with "01"
        And I press "Suivant"
        Then I should be on "/declaration/nature-infraction"

    @javascript
    Scenario: Submit the form with addressCountry is France for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        # Civil state
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_town"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        # Contact information
        And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I fill in "identity_contactInformation_addressNumber" with "01"
        And I select "1" from "identity_contactInformation_addressWay"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        # Corporation legal representative
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone" with "0602030405"
        And I select "1" from "identity_corporation_addressLocation_country"
        And I select "Paris (75)" from "identity_corporation_addressLocation_town"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I select "1" from "identity_corporation_addressWay"
        And I fill in "identity_corporation_addressNumber" with "01"
        And I press "Suivant"
        Then I should be on "/declaration/nature-infraction"

    @javascript
    Scenario: Submit the form with other country than France for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        # Civil state
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_town"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        # Contact information
        And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
        And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
        And I fill in "identity_contactInformation_addressNumber" with "01"
        And I select "1" from "identity_contactInformation_addressWay"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        # Corporation legal representative
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone" with "0602030405"
        And I select "2" from "identity_corporation_addressLocation_country"
        And I wait for the element "identity_corporation_addressLocation_town" to appear
        And I fill in "identity_corporation_addressLocation_town" with "Madrid"
        And I select "1" from "identity_corporation_addressWay"
        And I fill in "identity_corporation_addressNumber" with "01"
        And I press "Suivant"
        Then I should be on "/declaration/nature-infraction"

    @javascript
    Scenario: Submit the form with no required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 1 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 2 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 3 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 4 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 5 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I fill in "identity_corporation_phone" with "0602030405"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 6 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I fill in "identity_corporation_phone" with "0602030405"
        And I select "1" from "identity_corporation_addressLocation_country"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 7 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I fill in "identity_corporation_phone" with "0602030405"
        And I select "1" from "identity_corporation_addressLocation_country"
        And I select "Paris (75)" from "identity_corporation_addressLocation_town"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

    @javascript
    Scenario: Submit the form with only 8 required value for Corporation Legal Representative declarant
        Given I am on "/declaration/identite"
        When I click the "label[for='identity_declarantStatus_2']" element
        And I wait for the element "#form-identity" to appear
        And I fill in "identity_corporation_siren" with "123456789"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I select "1" from "identity_corporation_nationality"
        And I fill in "identity_corporation_phone" with "0602030405"
        And I select "1" from "identity_corporation_addressLocation_country"
        And I select "Paris (75)" from "identity_corporation_addressLocation_town"
        And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
        And I select "1" from "identity_corporation_addressWay"
        And I press "Suivant"
        Then I should be on "/declaration/identite"

