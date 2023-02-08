@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the victim declarant form

    Background:
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"

    Scenario: I can see the fields for Victim
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
        When I select "99100" from "identity_civilState_birthLocation_country"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I should see "Paris (75000)" in the "#identity_civilState_birthLocation_frenchTown" element
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I should not see a "identity_civilState_birthLocation_frenchTown" element
        And I should not see "Paris (75000)" in the "#identity_civilState_birthLocation_otherTown" element

    Scenario: Submit the form with minimal valid values for victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with another birthCountry than France for victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with another addressCountry than France for victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress" with "C. de Alcalá Madrid"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form without any required values
        When I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_civility" should have focus

    Scenario: Submit the form with only 1 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthName" should have focus

    Scenario: Submit the form with only 2 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_firstnames" should have focus

    Scenario: Submit the form with only 3 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthDate" should have focus

    Scenario: Submit the form with only 4 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_birthLocation_frenchTown" should have focus

    Scenario: Submit the form with only 5 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_civilState_job" should have focus

    Scenario: Submit the form with only 6 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And the field "identity_contactInformation_frenchAddress_address" should have focus

    Scenario: Submit the form with only 7 required value for Victim declarant
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

    Scenario: Submit the form with only 8 required value for Victim declarant
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

    Scenario: I fill the identity form as france connected, when I go back, the identity data should be saved
        Given I am on "/authentification"
        And I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I am on "/porter-plainte/identite"
        Then the "identity_declarantStatus_0" field should contain "1"

    Scenario: I fill the identity form as not france connected, when go back, the identity data should be saved
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Avocats" and click "31B1"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        When I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I am on "/porter-plainte/identite"
        Then the "identity_declarantStatus_0" field should contain "1"

