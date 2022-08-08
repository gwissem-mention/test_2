Feature:
    In order to show homepage
    As a user
    I need to see a black empty page

    @func
    Scenario: Show homepage on / route with 200 status code
        Given I am on "/"
        Then the response status code should be 200
        And I should see 1 "body" element
