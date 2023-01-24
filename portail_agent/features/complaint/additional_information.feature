Feature:
    In order to the complaint additional information page
    As a user
    I want to see the complaint additional information page

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I want to show complaint additional information page
        Given I am on "/plainte/informations-complementaires/1"
        Then the response status code should be 200
        And I should see a "body" element
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 4 "button[data-bs-toggle='modal']" element
        And I should see 21 "button" element
        And I should see the key "pel.send.to.lrp" translated
        And I should see the key "pel.reject" translated
        And I should see the key "pel.reasign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.photos.and.videos" translated
        And I should see the key "pel.cctv.present" translated
        And the "additional_information_cctvPresent_0" checkbox should be checked
        And I should see the key "pel.cctv.available" translated
        And the "additional_information_cctvAvailable_1" checkbox should be checked
        And I should see the key "pel.suspects" translated
        And I should see the key "pel.do.you.have.informations.on.potential.suspects" translated
        And the "additional_information_suspectsKnown_0" checkbox should be checked
        And I should see the key "pel.facts.suspects.informations.text" translated
        And the "additional_information_suspectsKnownText" field should contain "2 hommes"
        And I should see the key "pel.witnesses" translated
        And I should see the key "pel.facts.witnesses" translated
        And the "additional_information_witnessesPresent_0" checkbox should be checked
        And I should see the key "pel.facts.witnesses.information.text" translated
        And the "additional_information_witnessesPresentText" field should contain "Paul DUPONT"
        And I should see the key "pel.fsi.intervention" translated
        And I should see the key "pel.fsi.visit" translated
        And the "additional_information_fsiVisit_0" checkbox should be checked
        And I should see the key "pel.observation.made" translated
        And the "additional_information_observationMade_0" checkbox should be checked
        And I should see the key "pel.physical.violences.and.threats" translated
        And I should see the key "pel.victim.of.violence" translated
        And the "additional_information_victimOfViolence_1" checkbox should be checked
        And I should see the key "pel.description.of.facts" translated
        And the "additional_information_description" field should contain "Vol d'un Iphone 13"

    @func
    Scenario: I can see the comments space on the additional information page
        Given I am on "/plainte/informations-complementaires/3"
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
        Given I am on "/plainte/informations-complementaires/3"
        And I should not focus the "comment_content" element
        Then I press "complaint-comment-button"
        And I should focus the "comment_content" element

    @javascript
    Scenario: I can add a comment from the additional information page
        Given I am on "/plainte/informations-complementaires/7"
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        Then I fill in "comment_content" with "Ceci est un commentaire test."
        When I press "comment-button"
        And I should see 3 ".comment-right" element
        And the "#comments-feed-title" element should contain "Espace commentaires (6)"
