Feature:
    In order to show the faq
    As a user
    I want to see the faq page

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I want to show the faq page
        When I am on "/faq"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.faq" translated
