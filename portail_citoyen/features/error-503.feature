Feature:
    In order to show 503
    As a user

    @func
    Scenario: Show 503 on /_error/503 route with 503 status code and a body
        Given I am on "/_error/503"
        Then the response status code should be 503
        And I should see 1 "body" element

    @func
    Scenario Outline: Show 503 with all translated elements
        Given I am on "/_error/503"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                             |
            | pel.error.unavailable.title                       |
            | pel.error.generic.code.text                       |
            | pel.error.unavailable.lead                        |
            | pel.error.unavailable.text                        |
