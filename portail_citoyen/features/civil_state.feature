Feature:
    In order to show civil state
    As a user
    I need to see a page with a civil state form

    @func
    Scenario: Show etat civil page with form fields label translated
        Given I am on "/etat-civil"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "all.fields.are.required" translated
        And I should see the key "civility" translated
        And I should see the key "birth.name" translated
        And I should see the key "first.names" translated
        And I should see the key "birth.date" translated
        And I should see the key "birth.country" translated
        And I should see the key "birth.town" translated
        And I should see the key "birth.department" translated
        And I should see the key "nationality" translated
        And I should see the key "your.job" translated
        And I should see the key "validate" translated

    @javascript
    Scenario: Show etat civil page and submit the form with minimal valid values
    (without selecting country, France by default)
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I fill in "civil_state_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "civil_state_birthTown"
        And I wait for the "#civil_state_birthDepartment" field to contain "75"
        And I select "1" from "civil_state_nationality"
        And I select "1" from "civil_state_job"
        And I press "Valider"
        Then I should see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with all valid values
    with France country
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I fill in "civil_state_birthDate" with "01/01/2000"
        And I select "1" from "civil_state_birthCountry"
        And I select "Paris (75)" from "civil_state_birthTown"
        And I wait for the "#civil_state_birthDepartment" field to contain "75"
        And I select "1" from "civil_state_nationality"
        And I select "1" from "civil_state_job"
        And I press "Valider"
        Then I should see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with all valid values
    with another country than France
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I fill in "civil_state_birthDate" with "01/01/2000"
        And I select "2" from "civil_state_birthCountry"
        And I wait for the element "civil_state_birthTown" to appear
        And I fill in "civil_state_birthTown" with "Madrid"
        And I select "2" from "civil_state_nationality"
        And I select "2" from "civil_state_job"
        And I press "Valider"
        Then I should see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form without any required values
        Given I am on "/etat-civil"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with only 1 required value
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with only 2 required values
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with only 3 required values
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with only 4 required values
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I fill in "civil_state_birthDate" with "01/01/2000"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with only 5 required values
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I fill in "civil_state_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "civil_state_birthTown"
        And I wait for the "#civil_state_birthDepartment" field to contain "75"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with only 6 required values
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I fill in "civil_state_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "civil_state_birthTown"
        And I wait for the "#civil_state_birthDepartment" field to contain "75"
        And I select "1" from "civil_state_nationality"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show etat civil page and submit the form with invalid birthdate format (dd-mm-YYYY)
        Given I am on "/etat-civil"
        When I select "1" from "civil_state_civility"
        And I fill in "civil_state_birthName" with "Dupont"
        And I fill in "civil_state_firstnames" with "Jean Pierre Marie"
        And I fill in "civil_state_birthDate" with "01-01-2020"
        And I select "Paris (75)" from "civil_state_birthTown"
        And I wait for the "#civil_state_birthDepartment" field to contain "75"
        And I select "1" from "civil_state_nationality"
        And I select "1" from "civil_state_job"
        And I press "Valider"
        Then I should not see "The form is valid"

