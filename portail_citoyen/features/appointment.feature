Feature:
    In order to show a page to make an appointment
    As a user
    I need to see a title "Prise de RDV"

    @func
    Scenario: Show appointment page with "Prise de RDV" title
        Given I am on "/rendez-vous"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "pel.make.appointment" translated
        And I follow "Précédent"
        And I should be on "/recapitulatif"

    @func
    Scenario: Show appointment page with "Prise de RDV" title
        Given I am on "/rendez-vous"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "pel.make.appointment" translated
        And I follow "Suivant"
        And I should be on "/declaration/finalisation"