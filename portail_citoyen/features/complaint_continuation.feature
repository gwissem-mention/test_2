Feature:
    In order to show complaint continuation page
    As a user
    I need to see an empty page

    @func
    Scenario: Show complaint continuation page on /poursuivre route with 200 status code
        Given I am on "/poursuivre"
        Then the response status code should be 200
        And I should see 1 "body" element
