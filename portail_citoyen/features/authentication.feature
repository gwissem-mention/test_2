Feature:
    In order to show authentication page
    As a user
    I need to a button and a link

    @func
    Scenario: Show authentication page with a button and a link and click on the link
        Given I am on "/authentification"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "pel.to.log.in" translated
        And I should see the key "pel.identify.with" translated
        And I should see the key "pel.france.connect" translated
        And I should see the key "pel.what.is.france.connect" translated
        And I should see the key "pel.new.window" translated in the response
        And I should see the key "pel.continue.pel.without.log.in" translated
        And I should see the key "pel.continue.pel.without.log.in.explanation" translated
        And I follow "Continuer sans m'authentifier"
        And I am on "/identite"

    @javascript
    Scenario: Show authentication page with a button and a link and click on the button
        Given I am on "/authentification"
        Then I wait for the element "#france_connect_auth_button" to appear
        And I press "france_connect_auth_button"
        And I am on "/identite"
