Feature:
    In order to show the reporting
    As a user
    I want to see the reporting page

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I want to show the reporting page
        When I am on "/reporting"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.reporting" translated
        And I should see the key "pel.declarations.status" translated
        And I should see the key "pel.ongoing" translated
        And I should see 1 "#ongoing_number" element
        And the "#ongoing_number" element should contain "0"
