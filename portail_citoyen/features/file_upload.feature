Feature:
    In order to show the file upload demo page
    As a user
    I need to see a dropzone

    @func
    Scenario: I can see the file upload demo page
        Given I am on "/envois-fichiers"
        Then I should see "Fichiers"
        And I should see 1 "form" element
        And I should see 1 "div.dropzone" element
        And I should see 1 "button[type='submit']" element

    @javascript
    Scenario: I can upload a file to the dropzone widget
        Given I am on "/envois-fichiers"
        When I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "Enregistrer"
        And I wait for the element "ul" to appear
        Then I should see 1 "li" element
        And I should see 1 "a" element
