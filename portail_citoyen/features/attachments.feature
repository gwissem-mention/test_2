Feature:
    In order to show the complaint attachments file upload page
    As a user
    I need to see a dropzone

    @func
    Scenario: I can see the complaint attachments file upload page
        Given I am on "/pieces-complementaires"
        Then I should see the key "pel.attachments" translated
        And I should see the key "pel.attachments.upload" translated
        And I should see 1 "form" element
        And I should see 1 "div.dropzone" element
        And I should see 1 "button[type='submit']" element

    @javascript
    Scenario: I can upload a file to the dropzone widget
        Given I am on "/pieces-complementaires"
        When I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "Enregistrer"
        Then I should see 1 "li" element
        And I should see 2 "a" element
