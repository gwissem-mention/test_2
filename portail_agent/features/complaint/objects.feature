Feature:
    In order to the objects facts page
    As a user
    I want to see the objects facts page

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I want to show objects facts page
        Given I am on "/plainte/objets/1"
        Then the response status code should be 200
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
        And I should see the key "pel.description.of.facts" translated
        And I should see the key "pel.objects.concerned" translated
        And I should see the key "pel.additional.informations" translated
        And I should see the key "pel.stolen.objects" translated
        And I should see the key "pel.object.label" translated
        And the "facts_object_label" field should contain "Téléphone mobile"
        And I should see the key "pel.object.brand" translated
        And the "facts_object_brand" field should contain "Apple"
        And I should see the key "pel.object.estimated.amount" translated
        And the "facts_object_amount" field should contain "999"
        And I should see the key "pel.mobile.imei" translated
        And the "facts_object_imei" field should contain "1234567890"
        And I should see the key "pel.object.model" translated
        And the "facts_object_model" field should contain "Iphone 13"
        And I should see the key "pel.mobile.operator" translated
        And the "facts_object_operator" field should contain "Orange"
        And I should see the key "pel.phone.number.line" translated
        And the "facts_object_phoneNumber" field should contain "06 12 34 56 67"
        And I should see the key "pel.total.objects.concerned" translated
        And I should see the key "pel.there.is.a.total.of" translated
        And the "span#objects_number" element should contain "2"
        And I should see the key "pel.for.a.total.amount.of" translated
        And I should see "2 328 €"

    @func
    Scenario: I can click on the Go to facts button
        Given I am on "/plainte/objets/1"
        When I follow "Accéder à l'onglet : Informations complémentaires"
        And I should be on "/plainte/informations-complementaires/1"
        And the response status code should be 200

    @func
    Scenario: Scenario: I can see the comments space on the identity page
        Given I am on "/plainte/objets/3"
        And I should see a "#comment_content" element
        And I should see a ".comment-box" element
        And I should see a "#comments-feed-title" element
        And I should see the key "pel.comments.feed" translated
        And the "#comments-feed-title" element should contain "Fil de conversation - (5)"
        And I should see 3 ".comment-left" element
        And I should see 2 ".comment-right" element
        And I should see 5 "#comment-author" element
        And I should see 5 "#comment-published-at" element
        And I should see "Jean Dupont" in the ".comment-right" element
        And I should see "André Durant" in the ".comment-left" element

    @javascript
    Scenario: I can click the "Comment" button, and it focus the comment field
        Given I am on "/plainte/objets/3"
        And I should not focus the "comment_content" element
        Then I press "Commentaire"
        And I should focus the "comment_content" element
