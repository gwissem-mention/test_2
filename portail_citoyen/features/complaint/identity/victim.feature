@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the victim declarant form

    Background:
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I follow "Je confirme"

    Scenario: I can see the fields for Victim
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
        And I should see the key "pel.phone" translated
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
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with male job then female job
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I select "2" from "identity_civilState_civility"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteuse de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
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
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form with no phones filled
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_contactInformation_mobile_number" element
        And I should see a "#form-errors-identity_contactInformation_phone_number" element

    Scenario: Submit the form with another addressCountry than France for victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress_housenumber" with "14"
        And I fill in "identity_contactInformation_foreignAddress_type" with "Corto"
        And I fill in "identity_contactInformation_foreignAddress_street" with "de Alcalá"
        And I fill in "identity_contactInformation_foreignAddress_apartment" with "2"
        And I fill in "identity_contactInformation_foreignAddress_city" with "Madrid"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"

    Scenario: Submit the form without any required values
        When I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_civility" element

    Scenario: Submit the form with only 1 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthName" element

    Scenario: Submit the form with only 2 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_firstnames" element

    Scenario: Submit the form with only 3 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthDate" element

    Scenario: Submit the form with only 4 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_birthLocation_frenchTown" element

    Scenario: Submit the form with only 5 required value for Victim declarant
        When I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see a "#form-errors-identity_civilState_job" element

    Scenario: Submit the form with only 6 required value for Victim declarant
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

    Scenario: Submit the form with only 7 required value for Victim declarant
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

    Scenario: Submit the form with only 8 required value for Victim declarant
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

    Scenario: I fill the identity form as france connected, when I go back, the identity data should be saved
        Given I am on "/authentification"
        And I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/identite"
        When I click the "label[for=identity_declarantStatus_0]" element
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
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
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        When I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I am on "/porter-plainte/identite"
        Then the "identity_declarantStatus_0" field should contain "1"

    Scenario: I fill the identity form with an non etalab address, then I should see a google maps opened in the facts form
        When I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102030405"
        When I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        And I should see a "div#map" element
        And I should see a ".gm-style" element
        And the marker should be at latitude "48.8650197" and longitude "2.3758909"
