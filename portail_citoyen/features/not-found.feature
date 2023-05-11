Feature:
    In order to show 404
    As a user

    @func
    Scenario: Show 404 on /_error/404 route with 404 status code and a body
        Given I am on "/_error/404"
        Then the response status code should be 404
        And I should see 1 "body" element
        And I should see a "nav" element

    @func
    Scenario Outline: Show 404 with all translated elements
        Given I am on "/_error/404"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                             |
            | pel.not.found.title                               |
            | pel.not.found.text                                |
            | pel.not.found.can                                 |
            | pel.not.found.can.welcome                         |
            | pel.not.found.can.sitemap.1                       |
            | pel.not.found.can.sitemap.2                       |
            | pel.not.found.can.menu                            |
