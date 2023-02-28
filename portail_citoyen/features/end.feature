Feature:
    In order to show a page ending page
    As a user
    I need to see 1 thanks text, 1 appointment title, 1 opinion title, 2 smileys and 1 button to go to the homepage

    @func
    Scenario: I can see the ending page
        Given I am on "/fin"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "pel.end.thanks" translated
        And I should see the key "pel.your.appointment" translated
        And I should see the key "pel.your.opinion" translated
        And I should see the key "pel.back.to.homepage" translated

    @func
    Scenario: I can click on the "Je donne mon avis" button
        Given I am on "/fin"
        When I follow "Je donne mon avis"
        Then I should be on JeDonneMonAvis

    @func
    Scenario: I can click on the back to homepage button
        Given I am on "/fin"
        When I follow "Retour à l'accueil"
        Then the response status code should be 200
        And I should be on "/"
