Feature:
    In order to show a page that summarize the complaint
    As a user
    I need to see a title, 3 section titles, 1 previous button and 1 next button

    @javascript
    Scenario: I can click on the next button and be redirected to "/fin" when I am france connected
        Given I am on "/authentification"
        And I press "france_connect_auth_button"
        Given I am on "/recapitulatif"
        When I follow "Suivant"
        And I should be on "/fin"
        And I should not see the key "pel.your.appointment" translated
