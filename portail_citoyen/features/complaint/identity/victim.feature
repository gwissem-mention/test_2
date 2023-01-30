@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the victim declarant form

    Scenario: I can see the fields for Victim
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
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
        And I should see the key "pel.complaint.identity.declarant.status" translated

    Scenario: Change country from France to Spain and check the town field is cleared
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "99100" from "identity_civilState_birthLocation_country"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I should see "Paris (75000)" in the "#identity_civilState_birthLocation_frenchTown" element
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I should not see a "identity_civilState_birthLocation_frenchTown" element
        And I should not see "Paris (75000)" in the "#identity_civilState_birthLocation_otherTown" element

    Scenario: Submit the form with minimal valid values for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with birthCountry is France for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with another birthCountry than France for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with addressCountry is France for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I select "99100" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with another addressCountry than France for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress" with "C. de Alcalá Madrid España"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form without any required values
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 1 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""


    Scenario: Submit the form with only 2 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 3 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 4 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 5 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 6 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 7 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 8 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 9 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 10 required value for victim declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: I fill the identity form as france connected, when I reload the page, the identity data should be saved
        Given I am on "/authentification"
        And I press "france_connect_auth_button"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        And I wait 2000 ms
        And I reload the page
        And I click the "#identity_accordion_title" element
        Then the "identity_declarantStatus_0" field should contain "1"

    Scenario: I fill the identity form as not france connected, when I reload the page, the identity data should be saved
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0602030405"
        And I press "identity_submit"
        And I wait 2000 ms
        And I reload the page
        And I click the "#identity_accordion_title" element
        Then the "identity_declarantStatus_0" field should contain "1"

    Scenario: I can switch to afghanistan phone country and submit a valid phone number
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I click the ".iti__flag-container" element
        And I click the "li[data-country-code=af]" element
        And I fill in "identity_contactInformation_phone_number" with "70 123 4567"
        Then I should not see a "#form-errors-identity_contactInformation_phone_number" element

    Scenario: I cannot enter invalid chars for phone input
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        When I fill in "identity_contactInformation_phone_number" with "abcd0601020304$."
        Then the "identity_contactInformation_phone_number" field should contain "6 01 02 03 04"

    Scenario: I should see a error message when number is wrong
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        When I fill in "identity_contactInformation_phone_number" with "00010203040506"
        Then I should see the key "pel.phone.is.invalid" translated
