Feature:
    In order to show contact information
    As a user
    I need to see a page with a contact information form

    @func
    Scenario: Show contact information page with form fields label translated
        Given I am on "/coordonnees"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "all.fields.are.required" translated
        And I should see the key "address.country" translated
        And I should see the key "address.town" translated
        And I should see the key "address.department" translated
        And I should see the key "address.way" translated
        And I should see the key "address.number" translated
        And I should see the key "email" translated
        And I should see the key "mobile" translated
        And I should see the key "validate" translated

    @javascript
    Scenario: Show contact information page and submit the form with minimal valid values
    (without selecting country, France by default)
        Given I am on "/coordonnees"
        When I select "Paris (75)" from "contact_information_addressTown"
        And I wait for the "#contact_information_addressDepartment" field to contain "75"
        And I fill in "contact_information_addressNumber" with "01"
        And I select "1" from "contact_information_addressWay"
        And I fill in "contact_information_email" with "jean@test.com"
        And I fill in "contact_information_mobile" with "0602030405"
        And I press "Valider"
        Then I should see "The form is valid"

    @javascript
    Scenario: Show contact information page and submit the form with all valid values
    with France country
        Given I am on "/coordonnees"
        When I select "1" from "contact_information_addressCountry"
        And I select "Paris (75)" from "contact_information_addressTown"
        And I wait for the "#contact_information_addressDepartment" field to contain "75"
        And I fill in "contact_information_addressNumber" with "01"
        And I select "1" from "contact_information_addressWay"
        And I fill in "contact_information_email" with "jean@test.com"
        And I fill in "contact_information_mobile" with "0602030405"
        And I press "Valider"
        Then I should see "The form is valid"

    @javascript
    Scenario: Show contact information page and submit the form with all valid values
    with another country than France
        Given I am on "/coordonnees"
        When I select "2" from "contact_information_addressCountry"
        And I wait for the element "contact_information_addressTown" to appear
        And I fill in "contact_information_addressTown" with "Madrid"
        And I fill in "contact_information_addressNumber" with "01"
        And I select "1" from "contact_information_addressWay"
        And I fill in "contact_information_email" with "jean@test.com"
        And I fill in "contact_information_mobile" with "0602030405"
        And I press "Valider"
        Then I should see "The form is valid"

    @javascript
    Scenario: Show contact information page and submit the form without any required values
        Given I am on "/coordonnees"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show contact information page and submit the form with 1 required value
        Given I am on "/coordonnees"
        When I select "Paris (75)" from "contact_information_addressTown"
        And I wait for the "#contact_information_addressDepartment" field to contain "75"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show contact information page and submit the form with 2 required values
        Given I am on "/coordonnees"
        When I select "Paris (75)" from "contact_information_addressTown"
        And I wait for the "#contact_information_addressDepartment" field to contain "75"
        And I fill in "contact_information_addressNumber" with "01"
        And I press "Valider"
        Then I should not see "The form is valid"


    @javascript
    Scenario: Show contact information page and submit the form with 3 required values
        Given I am on "/coordonnees"
        When I select "Paris (75)" from "contact_information_addressTown"
        And I wait for the "#contact_information_addressDepartment" field to contain "75"
        And I fill in "contact_information_addressNumber" with "01"
        And I select "1" from "contact_information_addressWay"
        And I press "Valider"
        Then I should not see "The form is valid"

    @javascript
    Scenario: Show contact information page and submit the form with 4 required values
        Given I am on "/coordonnees"
        When I select "Paris (75)" from "contact_information_addressTown"
        And I wait for the "#contact_information_addressDepartment" field to contain "75"
        And I fill in "contact_information_addressNumber" with "01"
        And I select "1" from "contact_information_addressWay"
        And I fill in "contact_information_email" with "jean@test.com"
        And I press "Valider"
        Then I should not see "The form is valid"


    @javascript
    Scenario: Show contact information page and submit the form with invalid email (bad format)
        Given I am on "/coordonnees"
        When I select "Paris (75)" from "contact_information_addressTown"
        And I wait for the "#contact_information_addressDepartment" field to contain "75"
        And I fill in "contact_information_addressNumber" with "01"
        And I select "1" from "contact_information_addressWay"
        And I fill in "contact_information_email" with "jean"
        And I fill in "contact_information_mobile" with "0602030405"
        And I press "Valider"
        Then I should not see "The form is valid"
