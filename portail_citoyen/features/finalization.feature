Feature:
    In order to show a page to finish my complaint declaration
    As a user
    I need to see a title "Finalisation"

    @func
    Scenario: Show appointment page with "Finalisation" title
        Given I am on "/declaration/finalisation"
        And print last response
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "finalization" translated in the response
