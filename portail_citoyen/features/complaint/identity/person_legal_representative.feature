@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the person legal representative declarant form

    Scenario: I can select the person legal representative radio button
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
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
        And I should see the key "pel.email" translated
        And I should see the key "pel.mobile" translated
        And I should see the key "pel.next" translated

    Scenario: Submit the form with minimal valid values for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I select "1" from "identity_representedPersonCivilState_nationality"
        And I select "1" from "identity_representedPersonCivilState_job"
        And I fill in "identity_representedPersonContactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with birthCountry is France for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99100" from "identity_civilState_birthLocation_country"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I select "1" from "identity_representedPersonCivilState_nationality"
        And I select "1" from "identity_representedPersonCivilState_job"
        And I fill in "identity_representedPersonContactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with another birthCountry than France for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99134" from "identity_civilState_birthLocation_country"
        And I fill in "identity_civilState_birthLocation_otherTown" with "Madrid"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I select "1" from "identity_representedPersonCivilState_nationality"
        And I select "1" from "identity_representedPersonCivilState_job"
        And I fill in "identity_representedPersonContactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with addressCountry is France for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I select "99100" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I select "1" from "identity_representedPersonCivilState_nationality"
        And I select "1" from "identity_representedPersonCivilState_job"
        And I fill in "identity_representedPersonContactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form with another addressCountry than France for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "99100" from "identity_civilState_birthLocation_country"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I select "99134" from "identity_contactInformation_country"
        And I fill in "identity_contactInformation_foreignAddress" with "C. de Alcalá Madrid España"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0602030405"
        And I select "1" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I select "1" from "identity_representedPersonCivilState_nationality"
        And I select "1" from "identity_representedPersonCivilState_job"
        And I fill in "identity_representedPersonContactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should contain "style=\"display: block;\""

    Scenario: Submit the form without any required values
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 1 required value for person legal representative declarant
        Given I am on "/porter-plainte"
        When I click the "#identity_accordion_title" element
        And I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 2 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 3 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 4 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 5 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 6 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 7 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 8 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 9 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Submit the form with only 10 required value for person legal representative declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I press "identity_submit"
        Then the "#facts_accordion_item" element should not contain "style=\"display: block;\""

    Scenario: Select the Person Legal Representative and see "Sans Profession" in the job field
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        Then I should see "Sans Profession" in the "#identity_representedPersonCivilState_job" element

    Scenario: Submit the form with an invalid birth date (under 18) for declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I fill in "identity_civilState_birthDate" with "01/01/2020"
        And I press "identity_submit"
        Then I should see "Vous devez avoir plus de 18 ans"

    Scenario: Submit the form with an invalid birth date (over 120) for declarant
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"
        And I click the "#identity_accordion_title" element
        When I click the "label[for=identity_declarantStatus_1]" element
        And I fill in "identity_civilState_birthDate" with "01/01/1900"
        And I press "identity_submit"
        Then I should see "Vous devez avoir moins de 120 ans"
