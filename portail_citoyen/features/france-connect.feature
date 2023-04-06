@func
Feature:
    In order to transmit information on my behalf
    As a user
    I need to be able to connect via France Connect

    Scenario: France Connect successful authentication
        Given my declarant status is 1
        When I go to "/authentification?france_connected=1"
        Then I should be on "/porter-plainte/identite"
        And I should be connected as "Michel DUPONT"

    Scenario: Error on access token retrieval
        Given my declarant status is 1
        And An error will occur during France Connect access token retrieval
        When I go to "/authentification?france_connected=1"
        Then I should see "Echec de l'authentification"
        And I should not be connected

    Scenario: Error on user info retrieval
        Given my declarant status is 1
        And An error will occur during France Connect user info retrieval
        When I go to "/authentification?france_connected=1"
        Then I should see "Echec de l'authentification"
        And I should not be connected

    Scenario: Redirection to France Connect logout on app logout
        Given my declarant status is 1
        And follow redirects is disabled
        When I go to "/authentification?france_connected=1"
        And I follow the redirection
        Then I should be connected as "Michel DUPONT"
        When I go to "/logout"
        Then I should be redirected to the France Connect logout page
        And I should not be connected

    Scenario: FranceConnect button is not displayed when logged
        Given my declarant status is 1
        And I am connected as "Michel DUPONT" (michel-dupont-id)
        When I go to "/authentification"
        Then the FranceConnect button is not displayed

    Scenario: FranceConnect button is displayed when not logged
        Given my declarant status is 1
        When I go to "/authentification"
        Then the FranceConnect button is displayed
