Feature:
    In order to show 404
    As a user

    @func
    Scenario: Show 404 on /_error/404 route with 404 status code and a body
        Given I am on "/_error/404"
        Then the response status code should be 404
        And I should see 1 "body" element

    @func
    Scenario Outline: Show 404 with all translated elements
        Given I am on "/_error/404"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                             |
            | pel.error.not.found.title                         |
            | pel.error.generic.code.text                       |
            | pel.error.not.found.lead                          |
            | pel.error.not.found.text                          |
            | pel.error.not.found.btn.text                      |

    @func
    Scenario: When I click and the homepage button and be redirected button
        Given I am on "/_error/404"
        When I follow "Page d'accueil"
        Then I should be on "/"
