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
        Then the "#appointment_appointmentDate" element should be disabled
        Then the "#appointment_appointmentTime" element should be disabled
        And I should see the key "pel.appointment.planned.with.the.victim" translated
        When I am on homepage
        Then I should see "01/01/2025 10:00"

    @javascript
    Scenario: When I plan an appointment with the victim, the button "Send Report and Close" become "Close after the appointment"
        Given I am on "/plainte/rendez-vous/111"
        And I should see the key "pel.send.report.to.the.victim.and.close" translated
        And I should see a "#complaint-send-report-to-victim-button" element
        And I should not see the key "pel.close.after.appointment" translated
        And I should not see a "#complaint-close-after-appointment-button" element
        When I fill in "appointment_appointmentDate" with "01/01/2025"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "Valider le RDV avec le déclarant"
        Then I should not see the key "pel.send.report.to.the.victim.and.close" translated
        And I should not see a "#complaint-send-report-to-victim-button" element
        And I should see the key "pel.close.after.appointment" translated
        And I should see a "#complaint-close-after-appointment-button" element

    @javascript
    Scenario: I can toggle the close after the appointment modal
        Given I am on "/plainte/rendez-vous/111"
        Then I fill in "appointment_appointmentDate" with "01/01/2025"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "Valider le RDV avec le déclarant"
        When I press "Clôturer le PV suite au RDV"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.close.after.appointment" translated
        And I should see the key "pel.complaint.assignation" translated
        And I should see the key "pel.complaint.validation.sending.to.lrp" translated
        And I should see the key "pel.drop.report.and.send.to.the.victim" translated
        And I should see the key "pel.do.you.confirm.that.you.made.the.appointment" translated
        And I should see the key "pel.yes" translated
        And I should see the key "pel.no" translated
        And I should see the key "pel.upload.report.optional" translated
        And I should see the key "pel.drag.and.drop.or.click.here.to.browse" translated
        And I should see the key "pel.back" translated
        And I should see the key "pel.validate.the.report.and.close" translated
        When I press "complaint-validate-the-report-and-close-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can validate the close after appointment modal only if I have made the appointment
        Given I am on "/plainte/rendez-vous/111"
        Then I fill in "appointment_appointmentDate" with "01/01/2025"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "Valider le RDV avec le déclarant"
        When I press "Clôturer le PV suite au RDV"
        Then I should see a ".modal[aria-modal=true]" element
        And the "#complaint-validate-the-report-and-close-button-validate" element should be disabled
        When I click the "label[for=send_report_appointment_done_0]" element
        Then the "#complaint-validate-the-report-and-close-button-validate" element should not be disabled
        When I click the "label[for=send_report_appointment_done_1]" element
        Then the "#complaint-validate-the-report-and-close-button-validate" element should be disabled

    @javascript
    Scenario: I can submit the send report form successfully with no file
        Given I am on "/plainte/rendez-vous/111"
        Then I fill in "appointment_appointmentDate" with "01/01/2025"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "Valider le RDV avec le déclarant"
        When I press "Clôturer le PV suite au RDV"
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
        Then I fill in "appointment_appointmentDate" with "01/01/2025"
        And I fill in "appointment_appointmentTime" with "10:00am"
        And I press "Valider le RDV avec le déclarant"
        When I press "Clôturer le PV suite au RDV"
        Then I should see a ".modal[aria-modal=true]" element
        When I click the "label[for=send_report_appointment_done_0]" element
        When I attach the file "iphone.png" to ".dz-hidden-input" field
        And I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "complaint-validate-the-report-and-close-button-validate"
        And I should not see a ".modal[aria-modal=true]" element
        And I should see the key "pel.closed" translated
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated
