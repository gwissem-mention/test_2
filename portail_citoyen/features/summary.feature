Feature:
    In order to show a page that summarize the complaint
    As a user
    I need to see a title, 3 section titles, 1 previous button and 1 next button

    @func
    Scenario: I can see the summary page
        Given I am on "/recapitulatif"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "pel.complaint.summary" translated
        And I should see the key "pel.complaint.identity.declarant.status" translated
        And I should see the key "pel.complaint.identity" translated
        And I should see the key "pel.facts.description" translated
        And I should see the key "pel.facts.description" translated
        And I should see the key "pel.previous" translated
        And I should see the key "pel.next" translated
        And I should see 2 "a.fr-btn" element

    @func
    Scenario: I can click on the previous button and be redirected to "/faits"
        Given I am on "/recapitulatif"
        When I follow "Précédent"
        Then the response status code should be 200
        And I should be on "/faits"

    @func
    Scenario: I can click on the next button and be redirected to "/rendez-vous"
        Given I am on "/recapitulatif"
        When I follow "Suivant"
        Then the response status code should be 200
        And I should be on "/rendez-vous"

    @javascript
    Scenario: I can click on the next button and be redirected to "/fin" when I am france connected
        Given I am on "/authentification"
        And I press "france_connect_auth_button"
        Given I am on "/recapitulatif"
        When I follow "Suivant"
        And I should be on "/fin"
        And I should not see the key "pel.your.appointment" translated
