Feature:
    In order to show the reporting
    As a user
    I want to see the reporting page

    Background:
        Given I am authenticated with H3U3XCGF from PN

    @func
    Scenario: I want to show the reporting page
        When I am on "/reporting"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.reporting" translated
        And I should see the key "pel.declarations.status" translated
        And I should see the key "pel.ongoing" translated
        And I should see 1 "#complaints_ongoing" element
        And I should see 1 "#complaints_closed" element
        And the "#complaints_ongoing" element should contain "10"
        And the "#complaints_closed" element should contain "10"

    @javascript
    Scenario: As a supervisor, I should see the reporting of my unit, and I should be able to view the closed complaints
        Given I am on "/reporting"
        And I should see 1 "#complaints_closed" element
        When I follow "complaints_closed"
        Then I should be on "/?status=cloturee"
        And I should see 11 "tr" element
        And I should see 10 ".btn-dark" element

    @javascript
    Scenario: As a agent, I should see the reporting of my complaints, and I should be able to view the closed complaints
        Given I am authenticated with H3U3XCGD from PN
        When I am on "/reporting"
        And I should see 1 "#complaints_closed" element
        When I follow "complaints_closed"
        Then I should be on "/?status=cloturee"
        And I should see 2 "tr" element
        And I should see "Aucune donnée disponible dans le tableau"
