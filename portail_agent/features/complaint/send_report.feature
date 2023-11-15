Feature:
    In order to send the complaint's report
    As a User
    I want to be able to use the Send report functionality

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @javascript
    Scenario: I can toggle the send report to victim modal
        Given I am on "/plainte/recapitulatif/111"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.dropzone.default.message" translated
        And I should see the key "pel.drop.the.report.then.validate.the.sending.to.the.victim" translated
        And I should see the key "pel.cancel" translated
        And I should see the key "pel.close.declaration" translated
        When I press "complaint-send-report-to-the-victim-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can see form errors when the send report file field is empty
        Given I am on "/plainte/recapitulatif/111"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "complaint-send-report-to-the-victim-button-validate"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a "ul" element
        And I should see the key 'pel.you.must.choose.a.file' translated

    @javascript
    Scenario: I can see form errors when I submit another type file than PDF
        Given I am on "/plainte/recapitulatif/111"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "blank.xls" to ".dz-hidden-input" field
        When I press "complaint-send-report-to-the-victim-button-validate"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a "ul" element

    @javascript
    Scenario: I can submit the send report form successfully
        Given I am on "/plainte/recapitulatif/111"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "complaint-send-report-to-the-victim-button-validate"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated

    @javascript
    Scenario: I can submit the send report form successfully with a png file
        Given I am on "/plainte/recapitulatif/111"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "iphone.png" to ".dz-hidden-input" field
        And I press "complaint-send-report-to-the-victim-button-validate"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated

    @javascript
    Scenario: I can submit the send report form successfully with a jpg file and a pdf file
        Given I am on "/plainte/recapitulatif/111"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "iphone.png" to ".dz-hidden-input" field
        And I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "complaint-send-report-to-the-victim-button-validate"
        Then I should see 2 ".dz-preview" element
        And I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated

    @javascript
    Scenario: I can toggle the close after the appointment modal
        Given I am on "/plainte/recapitulatif/112"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.close.declaration" translated
        And I should see the key "pel.indicate.the.situation" translated
        And I should see the key "pel.the.appointment.took.place.in.videoconference" translated
        And I should see the key "pel.the.appointment.took.place.on.site" translated
        And I should see the key "pel.upload.report.optional" translated
        And I should see the key "pel.dropzone.default.message" translated
        And I should see the key "pel.cancel" translated
        When I press "complaint-validate-the-report-and-close-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: If I validate the close after appointment modal with to situation selected, I should see an error
        Given I am on "/plainte/recapitulatif/112"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "complaint-validate-the-report-and-close-button-validate"
        Then I should see a "#form-errors-send_report_appointment_done" element
        And I should see the key "pel.you.must.choose.a.situation" translated

    @javascript
    Scenario: I can submit the send report form successfully with no file if I made the appointment in videoconference
        Given I am on "/plainte/recapitulatif/112"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I click the "label[for=send_report_appointment_done_0]" element
        And I press "complaint-validate-the-report-and-close-button-validate"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see the key "pel.closed" translated
        And I should see a ".toast" element
        And I should see the key "pel.the.complaint.is.closed" translated

    @javascript
    Scenario: I can submit the send report form successfully with no file if I made the appointment in-person
        Given I am on "/plainte/recapitulatif/112"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I click the "label[for=send_report_appointment_done_1]" element
        And I press "complaint-validate-the-report-and-close-button-validate"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see the key "pel.closed" translated
        And I should see a ".toast" element
        And I should see the key "pel.the.complaint.is.closed" translated

    @javascript
    Scenario: I can submit the send report form successfully with a png file and a pdf file if I made the appointment in videoconference
        Given I am on "/plainte/recapitulatif/112"
        When I press "Clôturer"
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
    Scenario: I can submit the send report form successfully with a png file and a pdf file if I made the appointment in-person
        Given I am on "/plainte/recapitulatif/112"
        When I press "Clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I click the "label[for=send_report_appointment_done_1]" element
        When I attach the file "iphone.png" to ".dz-hidden-input" field
        And I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "complaint-validate-the-report-and-close-button-validate"
        And I should not see a ".modal[aria-modal=true]" element
        And I should see the key "pel.closed" translated
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated
