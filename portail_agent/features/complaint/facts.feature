Feature:
    In order to the complaint facts page
    As a user
    I want to see the complaint facts page

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I want to show complaint facts page
        Given I am on "/plainte/faits/1"
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
        And I should see the key "pel.nature.of.the.facts" translated
        And the "facts_natures_0" checkbox should be checked
        And the "facts_natures_1" checkbox should be checked
        And the "facts_natures_2" checkbox should not be checked
        And I should see the key "pel.location.of.facts" translated
        And I should see the key "pel.nature.place" translated
        And the "facts_place" field should contain "Restaurant"
        And I should see the key "pel.address.start.or.exact" translated
        And the "facts_startAddress" field should contain "25 Avenue Georges Pompidou, Lyon, 69003"
        And I should see the key "pel.address.end" translated
        And the "facts_endAddress" field should contain "Place Charles Hernu, Villeurbanne, 69100"
        And I should see the key "pel.description" translated
        And the "facts_addressAdditionalInformation" field should contain "Les faits se sont produits entre le restaurant et l'appartement d'un ami"
        And I should see the key "pel.facts.date.and.hour" translated
        And I should see the key "pel.facts.date" translated
        And I should see the key "pel.exact.date.known" translated
        And the "facts_exactDateKnown_0" checkbox should be checked
        And the "facts_startDate" field should contain "2022-12-01"
        And I should see the key "pel.facts.hour" translated
        And I should see the key "pel.do.you.know.hour.facts" translated
        And the "facts_exactHourKnown_1" checkbox should be checked
        And the "facts_startHour" field should contain "10:00"
        And the "facts_endHour" field should contain "11:00"

    @func
    Scenario: Scenario: I can see the comments space on the facts page
        Given I am on "/plainte/faits/3"
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
        Given I am on "/plainte/faits/3"
        And I should not focus the "comment_content" element
        Then I press "Commentaire"
        And I should focus the "comment_content" element
