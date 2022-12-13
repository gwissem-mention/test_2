Feature:
    In order to show the reporting
    As a user
    I want to see the reporting page

    @func
    Scenario: I want to show the reporting page
        When I am on "/reporting"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.reporting" translated
