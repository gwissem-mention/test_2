Feature:
    In order to show a complaint's appointment management page
    As a user
    I want to see the appointment informations

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I can see a sidebar with current user information and delegation modal
        Given I am on "/plainte/rendez-vous/91"
        Then I should see the key "pel.profile" translated
        And I should see the key "pel.close" translated
        And I should see the key "pel.display.settings" translated
        And I should see the key "pel.settings" translated
        And I should see the key "pel.rights.delegation" translated
        And I should see the key "pel.select.the.delegation.period" translated
        And I should see the key "pel.selected.delegation.period" translated
        And I should see the key "pel.from" translated
        And I should see the key "pel.to" translated
        And I should see the key "pel.select.one.or.more.delegated.agents" translated
        And I should see the key "pel.logout" translated

    @func
    Scenario: I can navigate to the appointment management page
        Given I am on "/plainte/recapitulatif/91"
        When I follow "Gestion de RDV"
        Then I should be on "/plainte/rendez-vous/91"
        And the response status code should be 200
        And I should see the key "pel.appointment.management" translated
        And I should see the key "pel.declarant" translated
        And I should see the key "pel.sir" translated
        And I should see the key "pel.mobile.phone" translated
        And I should see the key "pel.email" translated
        And I should see the key "pel.information.entered.by.the.victim.to.make.an.appointment" translated
        And I should see the key "pel.enter.the.date.of.the.appointment.with.the.victim" translated
        And I should see the key "pel.enter.the.time.of.the.appointment.with.the.victim" translated
        And I should see the key "pel.appointment.validate" translated
        And I should see "DUPONT"
        And I should see "Jean"
        And I should see "06 12 34 45 57"
        And I should see "jean.dupont@gmail.com"
        And I should see "Disponible entre 10h et 12h le lundi"

    @javascript
    Scenario: I can see form errors if the date is before today
        Given I am on "/plainte/rendez-vous/91"
        When I fill hidden field "appointment_appointmentDate" with "2023-01-01"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "appointment-confirm-button"

    @javascript
    Scenario: I can submit the appointment form successfully
        Given I am on "/plainte/rendez-vous/91"
        When I fill hidden field "appointment_appointmentDate" with "2025-01-01"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "appointment-confirm-button"
        Then the "#appointment_appointmentDate" element should be disabled
        Then the "#appointment_appointmentTime" element should be disabled
        When I am on homepage
        Then I should see "01/01/2025"

    @javascript
    Scenario: If there is no appointment planned, the Cancel and Modify appointment button should not be displayed
        Given I am on "/plainte/rendez-vous/91"
        Then I should not see a "appointment-cancel-button" element
        And I should not see a "appointment-modify-button" element

    @javascript
    Scenario: If there is an appointment planned, I can modify it
        Given I am on "/plainte/rendez-vous/101"
        And the "#appointment-modify-button" element should not be disabled
        When I press "Modifier RDV"
        Then the "#appointment_appointmentDate" element should not be disabled
        And the "#appointment_appointmentTime" element should not be disabled
        When I fill hidden field "appointment_appointmentDate" with "2025-01-06"
        And I fill in "appointment_appointmentTime" with "11:00am"
        And I press "appointment-confirm-button"
        When I am on homepage
        And  I click the "#my-declaration" element
        Then I should see "06/01/2025"

    @javascript
    Scenario: If there is an appointment planned, I can open and close the cancellation modal
        Given I am on "/plainte/rendez-vous/102"
        And the "#appointment-cancel-button" element should not be disabled
        When I press "appointment-cancel-button"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "Conserver le rendez-vous"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: If there is an appointment planned, I can cancel it
        Given I am on "/plainte/rendez-vous/102"
        And the "#appointment-cancel-button" element should not be disabled
        When I press "appointment-cancel-button"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "Confirmer l'annulation"
        Then I should not see a ".modal[aria-modal=true]" element
        And the "#appointment_appointmentDate" element should not be disabled
        And the "#appointment_appointmentTime" element should not be disabled
        When I am on homepage
        Then I should not see "06/01/2025"
