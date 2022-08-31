Feature:
  In order to fill a complaint
  As a user
  I want to see the corporation legal representative declarant form

  @javascript
  Scenario: I can select the Corporation Legal Representative radio button
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    Then I should see the key "all.fields.are.required" translated
    And I should see the key "civility" translated
    And I should see the key "birth.name" translated
    And I should see the key "first.names" translated
    And I should see the key "birth.date" translated
    And I should see the key "birth.country" translated
    And I should see the key "birth.town" translated
    And I should see the key "birth.department" translated
    And I should see the key "nationality" translated
    And I should see the key "your.job" translated
    And I should see the key "address.country" translated
    And I should see the key "address.town" translated
    And I should see the key "address.department" translated
    And I should see the key "address.way" translated
    And I should see the key "address.number" translated
    And I should see the key "email" translated
    And I should see the key "mobile" translated
    And I should see the key "corporation.siren" translated
    And I should see the key "corporation.name" translated
    And I should see the key "corporation.function" translated
    And I should see the key "corporation.nationality" translated
    And I should see the key "corporation.email" translated
    And I should see the key "corporation.phone" translated
    And I should see the key "corporation.address.country" translated
    And I should see the key "corporation.address.number" translated
    And I should see the key "corporation.address.way" translated
    And I should see the key "corporation.address.town" translated
    And I should see the key "corporation.address.department" translated
    And I should see the key "next" translated
    

  @javascript
  Scenario: Submit the form with minimal valid values for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_contactInformation_mobile" with "0602030405"
    And I fill in "identity_corporation_siren" with "123456789"
    And I fill in "identity_corporation_name" with "Test Company"
    And I fill in "identity_corporation_function" with "Developer"
    And I select "1" from "identity_corporation_nationality"
    And I fill in "identity_corporation_email" with "jean@test.com"
    And I fill in "identity_corporation_phone" with "0602030405"
    And I select "Paris (75)" from "identity_corporation_addressLocation_town"
    And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
    And I fill in "identity_corporation_addressNumber" with "01"
    And I select "1" from "identity_corporation_addressWay"
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
    And I select "1" from "identity_civilState_civility"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 2 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 3 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 4 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 5 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 6 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 7 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 8 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 9 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "123456789"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 10 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "123456789"
    And I fill in "identity_corporation_name" with "Test Company"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 11 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "123456789"
    And I fill in "identity_corporation_name" with "Test Company"
    And I fill in "identity_corporation_function" with "Developer"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 12 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "123456789"
    And I fill in "identity_corporation_name" with "Test Company"
    And I fill in "identity_corporation_function" with "Developer"
    And I select "1" from "identity_corporation_nationality"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 13 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "123456789"
    And I fill in "identity_corporation_name" with "Test Company"
    And I fill in "identity_corporation_function" with "Developer"
    And I fill in "identity_corporation_phone" with "0602030405"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 14 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "123456789"
    And I fill in "identity_corporation_name" with "Test Company"
    And I fill in "identity_corporation_function" with "Developer"
    And I select "1" from "identity_corporation_nationality"
    And I fill in "identity_corporation_email" with "jean@test.com"
    And I fill in "identity_corporation_phone" with "0602030405"
    And I select "Paris (75)" from "identity_corporation_addressLocation_town"
    And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with only 15 required value for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "123456789"
    And I fill in "identity_corporation_name" with "Test Company"
    And I fill in "identity_corporation_function" with "Developer"
    And I select "1" from "identity_corporation_nationality"
    And I fill in "identity_corporation_email" with "jean@test.com"
    And I fill in "identity_corporation_phone" with "0602030405"
    And I select "Paris (75)" from "identity_corporation_addressLocation_town"
    And I wait for the "#identity_corporation_addressLocation_department" field to contain "75"
    And I select "1" from "identity_corporation_addressWay"
    And I press "Suivant"
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with invalid siren (too short) for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "1"
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
    Then I should be on "/declaration/identite"

  @javascript
  Scenario: Submit the form with invalid siren (letters) for Corporation Legal Representative declarant
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#form-identity" to appear
    And I select "1" from "identity_civilState_civility"
    And I fill in "identity_civilState_birthName" with "Dupont"
    And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
    And I fill in "identity_civilState_birthDate" with "01/01/2000"
    And I select "Paris (75)" from "identity_civilState_birthLocation_town"
    And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
    And I select "1" from "identity_civilState_nationality"
    And I select "1" from "identity_civilState_job"
    And I select "Paris (75)" from "identity_contactInformation_addressLocation_town"
    And I wait for the "#identity_contactInformation_addressLocation_department" field to contain "75"
    And I fill in "identity_contactInformation_addressNumber" with "01"
    And I select "1" from "identity_contactInformation_addressWay"
    And I fill in "identity_contactInformation_email" with "jean@test.com"
    And I fill in "identity_corporation_siren" with "ABCDEFGHI"
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
    Then I should be on "/declaration/identite"

