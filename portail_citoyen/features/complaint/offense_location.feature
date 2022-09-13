Feature:
    In order to fill a complaint
    As a user
    I want to see the offense nature step page

    @func
    Scenario: I can click on the previous button
        Given I am on "/declaration/lieu"
        Then the response status code should be 200
        And I should see the key "ministry" translated
        And I should see the key "inside" translated
        And I should see the key "and.overseas" translated
        And I should see the key "complaint.location" translated
        And I follow "Précédent"
        And I should be on "/declaration/nature-infraction"

    @func
    Scenario: I can click on the next button
        Given I am on "/declaration/lieu"
        Then the response status code should be 200
        And I should see the key "ministry" translated
        And I should see the key "inside" translated
        And I should see the key "and.overseas" translated
        And I should see the key "complaint.location" translated
        And I follow "Suivant"
        And I should be on "/declaration/rendez-vous"
