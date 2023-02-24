Feature:
    In order to the complaint identity page
    As a user
    I want to see the complaint identity page

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I want to show complaint identity page
        Given I am on "/plainte/identite/1"
        And the response status code should be 200
        And I should see a "body" element
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 4 "button[data-bs-toggle='modal']" element
        And I should see 20 "button" element
        And I should see the key "pel.send.to.lrp" translated
        And I should see the key "pel.reject" translated
        And I should see the key "pel.reasign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.declarant.civil.status" translated
        And the "identity_civility_0" checkbox should be checked
        And the "identity_civility_1" checkbox should not be checked
        And I should see the key "pel.lastname" translated
        And the "identity_lastname" field should contain "MILLET"
        And I should see the key "pel.firstname" translated
        And the "identity_firstname" field should contain "Philippine"
        And I should see the key "pel.birth.date" translated
        And the "identity_birthday" field should contain "1980-05-20"
        And I should see the key "pel.birth.country" translated
        And the "identity_birthCountry" field should contain "France"
        And I should see the key "pel.birth.city" translated
        And the "identity_birthCity" field should contain "Grenoble"
        And I should see the key "pel.declarant.contact.information" translated
        And I should see the key "pel.phone.number" translated
        And the "identity_phone" field should contain "+33 7 59 13 96 20"
        And I should see the key "pel.email" translated
        And the "identity_email" field should contain "louis86@peron.fr"
        And I should see the key "pel.sms.notification" translated
        And I should see the key "pel.want.to.receive.sms.notifications" translated
        And the "identity_smsNotifications" checkbox should not be checked
        And I should see the key "pel.declarant.address" translated
        And the "identity_address" field should contain "62, place de Samson, Bordeaux, 33000"
        And I should see the key "pel.address.france.label" translated
        And I should see the key "pel.declarant.situation" translated
        And I should see the key "pel.declarant.complain.as" translated
        And the "identity_declarantStatus" field should contain "1"

    @func
    Scenario: I can click on the Go to facts button
        Given I am on "/plainte/identite/1"
        When I follow "Accéder à l'onglet : Description des faits"
        And I should be on "/plainte/faits/1"
        And the response status code should be 200

    @func
    Scenario: Scenario: I can see the comments space on the identity page
        Given I am on "/plainte/identite/5"
        And I should see a "#comment_content" element
        And I should see a ".comment-box" element
        And I should see a "#comments-feed-title" element
        And I should see the key "pel.comments.feed" translated
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        And I should see 3 ".comment-left" element
        And I should see 2 ".comment-right" element
        And I should see 5 "#comment-author" element
        And I should see 5 "#comment-published-at" element
        And I should see "Jean Dupont" in the ".comment-right" element
        And I should see "André Durant" in the ".comment-left" element

    @javascript
    Scenario: I can click the "Comment" button, and it focus the comment field
        Given I am on "/plainte/identite/5"
        And I should not focus the "comment_content" element
        Then I press "complaint-comment-button"
        And I should focus the "comment_content" element

    @javascript
    Scenario: I can add a comment from the identity page
        Given I am on "/plainte/identite/34"
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        Then I fill in "comment_content" with "Ceci est un commentaire test."
        When I press "comment-button"
        And I should see 3 ".comment-right" element
        And the "#comments-feed-title" element should contain "Espace commentaires (6)"
