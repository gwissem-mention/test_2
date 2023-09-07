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
    Scenario: When I plan an appointment with the victim, the button "Send Report and Close" become "Close after the appointment"
        Given I am on "/plainte/rendez-vous/111"
        And I should see the key "pel.close.declaration" translated
        And I should see a "#complaint-send-report-to-victim-button" element
        And I should not see a "#complaint-close-after-appointment-button" element
        When I fill hidden field "appointment_appointmentDate" with "2025-01-01"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "appointment-confirm-button"
        Then I should not see the key "pel.send.report.to.the.victim.and.close" translated
        And I should not see a "#complaint-send-report-to-victim-button" element
        And I should see the key "pel.close.declaration" translated
        And I should see a "#complaint-close-after-appointment-button" element

    @javascript
    Scenario: I can toggle the close after the appointment modal
        Given I am on "/plainte/rendez-vous/111"
        Then I fill hidden field "appointment_appointmentDate" with "2025-01-01"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "appointment-confirm-button"
        When I press "complaint-close-after-appointment-button"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.close.declaration" translated
        And I should see the key "pel.indicate.the.situation" translated
        And I should see the key "pel.the.appointment.took.place.in.visioconference" translated
        And I should see the key "pel.the.appointment.took.place.on.site" translated
        And I should see the key "pel.upload.report.optional" translated
        And I should see the key "pel.dropzone.default.message" translated
        And I should see the key "pel.cancel" translated
        When I press "complaint-validate-the-report-and-close-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can validate the close after appointment modal only if I have made the appointment
        Given I am on "/plainte/rendez-vous/111"
        Then I fill hidden field "appointment_appointmentDate" with "2025-01-01"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "appointment-confirm-button"
        When I press "complaint-close-after-appointment-button"
        Then I should see a ".modal[aria-modal=true]" element
        And the "#complaint-validate-the-report-and-close-button-validate" element should be disabled
        When I click the "label[for=send_report_appointment_done_0]" element
        Then the "#complaint-validate-the-report-and-close-button-validate" element should not be disabled
        When I click the "label[for=send_report_appointment_done_1]" element
        Then the "#complaint-validate-the-report-and-close-button-validate" element should be disabled

    @javascript
    Scenario: I can submit the send report form successfully with no file
        Given I am on "/plainte/rendez-vous/111"
        Then I fill hidden field "appointment_appointmentDate" with "2025-01-01"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "appointment-confirm-button"
        When I press "complaint-close-after-appointment-button"
        Then I should see a ".modal[aria-modal=true]" element
        When I click the "label[for=send_report_appointment_done_0]" element
        And I press "complaint-validate-the-report-and-close-button-validate"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see the key "pel.closed" translated
        And I should see a ".toast" element
        And I should see the key "pel.the.complaint.is.closed" translated

    @javascript
    Scenario: I can submit the send report form successfully with a png file and a pdf file
        Given I am on "/plainte/rendez-vous/111"
        Then I fill hidden field "appointment_appointmentDate" with "2025-01-01"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "appointment-confirm-button"
        When I press "complaint-close-after-appointment-button"
        Then I should see a ".modal[aria-modal=true]" element
        When I click the "label[for=send_report_appointment_done_0]" element
        When I attach the file "iphone.png" to ".dz-hidden-input" field
        And I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "complaint-validate-the-report-and-close-button-validate"
        And I should not see a ".modal[aria-modal=true]" element
        And I should see the key "pel.closed" translated
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated

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
        Then the "#appointment_appointmentDate" element should be disabled
        Then the "#appointment_appointmentTime" element should be disabled
        When I am on homepage
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
