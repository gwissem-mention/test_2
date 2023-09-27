Feature:
    In order to show the reporting
    As a user
    I want to see the reporting page

    Background:
        Given I am authenticated with ZSBVHOAY from PN

    @func
    Scenario: I want to show the reporting page
        When I am on "/reporting"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.reporting" translated
        And I should see the key "pel.declarations.status" translated
        And I should see the key "pel.assignment.pending" translated
        And I should see the key "pel.ongoing.lrp" translated
        And I should see the key "pel.appointment.pending" translated
        And I should see the key "pel.unit.redirection.pending" translated
        And I should see the key "pel.closed" translated
        And I should see 1 "#complaints_assignment_pending" element
        And I should see 1 "#complaints_ongoing_lrp" element
        And I should see 1 "#complaints_appointment_pending" element
        And I should see 1 "#complaints_unit_redirection_pending" element
        And I should see 1 "#complaints_closed" element
        And the "#complaints_assignment_pending" element should contain "10"
        And the "#complaints_ongoing_lrp" element should contain "10"
        And the "#complaints_appointment_pending" element should contain "10"
        And the "#complaints_unit_redirection_pending" element should contain "10"
        And the "#complaints_closed" element should contain "10"

    @javascript
    Scenario: As a supervisor, I should see the reporting of my unit, and I should be able to view the closed complaints
        Given I am on "/reporting"
        And I should see 1 "#complaints_closed" element
        When I follow "complaints_closed"
        Then I should be on "/?status=cloturee"
        And I should see 11 "tr" element
        And I should see 10 ".background-green" element

    @javascript
    Scenario: As a agent, I should see the reporting of my complaints, and I should be able to view the closed complaints
        Given I am authenticated with PMAXHQMC from PN
        When I am on "/reporting"
        And I should see 1 "#complaints_closed" element
        When I follow "complaints_closed"
        Then I should be on "/mes-plaintes?status=cloturee"
        And I should see 2 "tr" element
        And I should see "Aucune donnée disponible dans le tableau"

    @javascript
    Scenario: As a supervisor, I should see the reporting of my unit, and I should be able to view the ongoing LRP complaints
        Given I am on "/reporting"
        And I should see 1 "#complaints_ongoing_lrp" element
        When I follow "complaints_ongoing_lrp"
        Then I should be on "/?status=en-cours-lrp"
        And I should see 11 "tr" element
        And I should see 10 ".background-yellow" element

    @javascript
    Scenario: As a agent, I should see the reporting of my complaints, and I should be able to view the ongoing LRP complaints
        Given I am authenticated with WTDAXALL from PN
        When I am on "/reporting"
        And I should see 1 "#complaints_ongoing_lrp" element
        When I follow "complaints_ongoing_lrp"
        Then I should be on "/mes-plaintes?status=en-cours-lrp"
        And I should see 11 "tr" element
        And I should see 10 ".background-yellow" element

    @javascript
    Scenario: As a supervisor, I should see the reporting of my unit, and I should be able to view the appointment pending complaints
        Given I am on "/reporting"
        And I should see 1 "#complaints_appointment_pending" element
        When I follow "complaints_appointment_pending"
        Then I should be on "/?status=attente-rdv"
        And I should see 11 "tr" element
        And I should see 11 ".background-cumulus" element

    @javascript
    Scenario: As a agent, I should see the reporting of my complaints, and I should be able to view the appointment pending complaints
        Given I am authenticated with WTDAXALL from PN
        When I am on "/reporting"
        And I should see 1 "#complaints_appointment_pending" element
        When I follow "complaints_appointment_pending"
        Then I should be on "/mes-plaintes?status=attente-rdv"
        And I should see 2 "tr" element
        And I should see "Aucune donnée disponible dans le tableau"

    @javascript
    Scenario: As a supervisor, I should see the reporting of my unit, and I should be able to view the assignment pending complaints
        Given I am on "/reporting"
        And I should see 1 "#complaints_assignment_pending" element
        When I follow "complaints_assignment_pending"
        Then I should be on "/?status=a-attribuer"
        And I should see 11 "tr" element
        And I should see 10 ".background-blue" element

    @javascript
    Scenario: As a agent, I should see the reporting of my complaints, and I should not be able to view the assignment pending complaints
        Given I am authenticated with WTDAXALL from PN
        When I am on "/reporting"
        And I should see 0 "#complaints_assignment_pending" element

    @javascript
    Scenario: As a supervisor, I should see the reporting of my unit, and I should be able to view the unit reassignment pending complaints
        Given I am on "/reporting"
        And I should see 1 "#complaints_unit_redirection_pending" element
        When I follow "complaints_unit_redirection_pending"
        Then I should be on "/?status=attente-reorientation"
        And I should see 11 "tr" element
        And I should see 11 ".background-cumulus" element

    @javascript
    Scenario: As a agent, I should see the reporting of my complaints, and I should be able to view the unit reassignment pending complaints
        Given I am authenticated with WTDAXALL from PN
        When I am on "/reporting"
        And I should see 1 "#complaints_unit_redirection_pending" element
        When I follow "complaints_unit_redirection_pending"
        Then I should be on "/mes-plaintes?status=attente-reorientation"
        And I should see 2 "tr" element
        And I should see "Aucune donnée disponible dans le tableau"
