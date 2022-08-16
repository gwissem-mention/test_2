Feature:
    In order to show a page to authorize to use my personal data
    As a user
    I need to see a checkbox and a link

    @func
    Scenario: Show visitor agreement page with information message
    a checkbox to use my personal data and a law information message
    and click on the checkbox and submit the form
        Given I am on "/visitor-agreement"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see 1 "input[type=checkbox]" element
        And the checkbox "consent_agree" should be unchecked
        And I should see 1 "label" element
        And I should see the key "visitor.agree" translated
        And I should see 1 "p" element
        And I should see the key "law.informations" translated
        And I should see 1 "button" element
        And I should see the key "keep.going" translated
        When I check "consent_agree"
        And I press "keep.going"
        Then I am on "/declaration/identite"

    @func
    Scenario: Show visitor agreement page with information message
    a checkbox to use my personal data and a law information message
    and submit the form without clicking on the checkbox
        Given I am on "/visitor-agreement"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see 1 "input[type=checkbox]" element
        And the checkbox "consent_agree" should be unchecked
        And I should see 1 "label" element
        And I should see the key "visitor.agree" translated
        And I should see 1 "p" element
        And I should see the key "law.informations" translated
        And I should see 1 "button" element
        And I should see the key "keep.going" translated
        And I press "keep.going"
        And I am on "/visitor-agreement"
