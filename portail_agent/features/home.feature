Feature:
    In order to show the homepage
    As a user
    I want to see the homepage with table informations

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I can navigate on the homepage and I should see a header and an image
        When I am on the homepage
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see a ".avatar" element
        And I should see "TD" in the ".avatar" element
        And I should see the key "pel.search" translated
        And I should see the key "pel.agent.complaint.online" translated
        And I should see the key "pel.header.baseline" translated
        And I should see the key "pel.complaint.online.portal" translated
        And I should see "Brigade de proximité de Voiron"
        And I should see the key "pel.home" translated
        And I should see the key "pel.reporting" translated
        And I should see the key "pel.faq" translated
        And I should see a "table" element
        And I should see 26 "tr" element
        And I should see the key "pel.deposit.date" translated
        And I should see the key "pel.facts" translated
        And I should see the key "pel.facts.date" translated
        And I should see the key "pel.alert" translated
        And I should see the key "pel.meeting.date" translated
        And I should see the key "pel.firstname.lastname" translated
        And I should see the key "pel.status" translated
        And I should see the key "pel.a.opj.name" translated
        And I should see the key "pel.declaration.number" translated
        And I should see the key "pel.comments" translated
        And I should see 5 ".btn-primary" element
        And I should see 5 ".btn-secondary" element
        And I should see 5 ".btn-danger" element
        And I should see 5 ".btn-warning" element
        And I should see 5 ".btn-success" element


    @func
    Scenario: I can click on a complaint link on the table
        Given I am on the homepage
        When I follow "PEL-2022-00000001"
        Then I am on "/plainte/recapitulatif/1"
        And the response status code should be 200

    @func
    Scenario: I can navigate from the homepage to the homepage
        When I am on the homepage
        And I follow "Accueil"
        Then I am on "/"
        And I should see the key "pel.home" translated

    @func
    Scenario: I can navigate from the homepage to the reporting page
        When I am on the homepage
        And I follow "Reporting"
        Then I am on "/reporting"
        And I should see the key "pel.reporting" translated

    @func
    Scenario: I can navigate from the homepage to the FAQ page
        When I am on the homepage
        And I follow "FAQ"
        Then I am on "/faq"
        And I should see the key "pel.faq" translated

    @javascript
    Scenario: I can click on a assignment notif and I should be redirected on the complaint and the notif should be removed
        Given I am authenticated with H3U3XCGD from PN
        And I am on the homepage
        When I click the "#notifications-dropdown" element
        Then I should see "La déclaration PEL-2022-00000001 vient de vous être attribuée"
        When I follow "La déclaration PEL-2022-00000001 vient de vous être attribuée"
        Then I should be on "/plainte/recapitulatif/1"
        When I click the "#notifications-dropdown" element
        Then I should not see "La déclaration PEL-2022-00000001 vient de vous être attribuée"

