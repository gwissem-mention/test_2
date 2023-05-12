Feature:
    In order to show a complaint's appointment management page
    As a user
    I want to see the appointment informations

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I can navigate to the appointment management page
        Given I am on "/plainte/recapitulatif/91"
        When I follow "Gestion RDV déclarant"
        Then I should be on "/plainte/rendez-vous/91"
        And the response status code should be 200
        And I should see the key "pel.appointment.management" translated
        And I should see the key "pel.civility" translated
        And I should see the key "pel.sir" translated
        And I should see the key "pel.lastname" translated
        And I should see the key "pel.firstname" translated
        And I should see the key "pel.phone" translated
        And I should see the key "pel.email" translated
        And I should see the key "pel.information.entered.by.the.victim.to.make.an.appointment" translated
        And I should see the key "pel.enter.the.date.and.time.of.the.appointment.with.the.victim" translated
        And I should see the key "pel.appointment.cancel" translated
        And I should see the key "pel.appointment.modify" translated
        And I should see the key "pel.appointment.validate" translated
        And I should see "DUPONT"
        And I should see "Jean"
        And I should see "06 12 34 45 57"
        And I should see "jean.dupont@gmail.com"
        And I should see "Disponible entre 10h et 12h le lundi"

    @javascript
    Scenario: I can see form errors if the date is before today
        Given I am on "/plainte/rendez-vous/91"
        When I fill in "appointment_appointmentDate" with "01/01/2023"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "Valider le RDV avec le déclarant"
        Then I should see 1 ".invalid-feedback" element

    @javascript
    Scenario: I can submit the appointment form successfully
        Given I am on "/plainte/rendez-vous/91"
        And I should not see the key "pel.appointment.planned.with.the.victim" translated
        When I fill in "appointment_appointmentDate" with "01/01/2025"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "Valider le RDV avec le déclarant"
        Then the field "#appointment_appointmentDate" should be disabled
        Then the field "#appointment_appointmentTime" should be disabled
        And I should see the key "pel.appointment.planned.with.the.victim" translated
        When I am on homepage
        Then I should see "01/01/2025 10:00"