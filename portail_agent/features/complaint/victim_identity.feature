Feature:
    In order to show a complaint's victim identity page
    As a user
    I want to see the victim identity information

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I want to show complaint victim identity page as a person legal representative
        Given I am on "/plainte/victime/151"
        And the response status code should be 200
        And I should see a "body" element
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 5 "button[data-bs-toggle='modal']" element
        And I should see 29 "button" element
        And I should see the key "pel.send.to.lrp" translated
        And I should see the key "pel.reject" translated
        And I should see the key "pel.unit.reassign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.victim.civil.status" translated
        And the "identity_civility_0" checkbox should not be checked
        And the "identity_civility_1" checkbox should be checked
        And I should see the key "pel.lastname" translated
        And the "identity_lastname" field should contain "DUPONT"
        And I should see the key "pel.firstname" translated
        And the "identity_firstname" field should contain "Jeremy"
        And I should see the key "pel.birth.date" translated
        And the "identity_birthday" field should contain "2000-02-14"
        And I should see the key "pel.birth.country" translated
        And the "identity_birthCountry" field should contain "France"
        And I should see the key "pel.birth.city" translated
        And the "identity_birthCity" field should contain "Meudon"
        And I should see the key "pel.birth.department" translated
        And the "identity_birthDepartment" field should contain "Hauts-de-Seine"
        And I should see the key "pel.job" translated
        And the "identity_job" field should contain "Etudiant"
        And I should see the key "pel.victim.contact.information" translated
        And I should see the key "pel.phone.number" translated
        And the "identity_mobilePhone" field should contain "06 76 54 32 10"
        And I should see the key "pel.email" translated
        And the "identity_email" field should contain "jeremy.dupont@gmail.com"
        And I should see the key "pel.victim.address" translated
        And the "identity_address" field should contain "15 Rue PAIRA, Meudon, 92190"
        And I should see the key "pel.address.france.label" translated

    @func
    Scenario: I want to show complaint victim identity page as a corporation representative
        Given I am on "/plainte/victime/161"
        And the response status code should be 200
        And I should see a "body" element
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 5 "button[data-bs-toggle='modal']" element
        And I should see 29 "button" element
        And I should see the key "pel.send.to.lrp" translated
        And I should see the key "pel.reject" translated
        And I should see the key "pel.unit.reassign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.moral.victim.information" translated
        And I should see the key "pel.siren.number" translated
        And the "corporation_sirenNumber" field should contain "123456789"
        And I should see the key "pel.company.name" translated
        And the "corporation_companyName" field should contain "Netflix"
        And I should see the key "pel.declarant.position" translated
        And the "corporation_declarantPosition" field should contain "PDG"
        And I should see the key "pel.nationality" translated
        And the "corporation_nationality" field should contain "Française"
        And I should see the key "pel.contact.email" translated
        And the "corporation_contactEmail" field should contain "pdg@netflix.com"
        And I should see the key "pel.phone" translated
        And the "corporation_phone" field should contain "0612345678"
        And I should see the key "pel.country" translated
        And the "corporation_country" field should contain "France"
        And I should see the key "pel.address" translated

    @func
    Scenario: I can click on the Go to facts button
        Given I am on "/plainte/victime/151"
        When I follow "Accéder à l'onglet : Description des faits"
        And I should be on "/plainte/faits/151"
        And the response status code should be 200

    @func
    Scenario: Scenario: I can see the comments space on the victim identity page
        Given I am on "/plainte/victime/151"
        And I should see a "#comment_content" element
        And I should see a ".comment-box" element
        And I should see a "#comments-feed-title" element
        And I should see the key "pel.comments.feed" translated
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        And I should see 5 ".comment-left" element
        And I should see 0 ".comment-right" element
        And I should see 5 "#comment-author" element
        And I should see 5 "#comment-published-at" element
        And I should see "Jean DUPONT" in the ".comment-left" element

    @javascript
    Scenario: I can click the "Comment" button, and it focus the comment field
        Given I am on "/plainte/victime/151"
        And I should not focus the "comment_content" element
        Then I press "complaint-comment-button"
        And I should focus the "comment_content" element

    @javascript
    Scenario: I can add a comment from the victim identity page
        Given I am on "/plainte/victime/151"
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        Then I fill in "comment_content" with "Ceci est un commentaire test."
        When I press "comment-button"
        And I should see 1 ".comment-right" element
        And the "#comments-feed-title" element should contain "Espace commentaires (6)"
