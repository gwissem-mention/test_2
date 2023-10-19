Feature:
    In order to show error page
    As a user

    @func
    Scenario: Show 500 on /_error/500 route with 500 status code and a body
        Given I am on "/_error/500"
        Then the response status code should be 500
        And I should see 1 "body" element

    @func
    Scenario Outline: Show 500 with all translated elements
        Given I am on "/_error/500"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                             |
            | pel.error.generic.title                           |
            | pel.error.generic.code.text                       |
            | pel.error.generic.lead                            |
            | pel.error.generic.text                            |

    @func
    Scenario: Show 403 on /_error/403 route with 403 status code and a body
        Given I am on "/_error/403"
        Then the response status code should be 403
        And I should see 1 "body" element

    @func
    Scenario Outline: Show 403 with all translated elements
        Given I am on "/_error/403"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                             |
            | pel.error.generic.title                           |
            | pel.error.generic.code.text                       |
            | pel.error.generic.lead                            |
            | pel.error.generic.text                            |

