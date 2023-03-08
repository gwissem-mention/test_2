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
    Scenario: I can click on a complaint link on the table
        Given I am on the homepage
        When I follow "PEL-2023-00000071"
        And I go to the tab 2
        Then I should be on "/plainte/recapitulatif/71"

    @javascript
    Scenario: I can click on a assignment notif and I should be redirected on the complaint and the notif should be removed
        Given I am authenticated with H3U3XCGD from PN
        And I am on the homepage
        When I click the "#notifications-dropdown" element
        Then I should see "La déclaration PEL-2023-00000001 vient de vous être attribuée"
        When I follow "La déclaration PEL-2023-00000001 vient de vous être attribuée"
        Then I should be on "/plainte/recapitulatif/1"
        When I click the "#notifications-dropdown" element
        Then I should not see "La déclaration PEL-2023-00000001 vient de vous être attribuée"

    @javascript
    Scenario: I should see a table datatables of 25 entries
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        Then I should see a "table#datatable" element
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
        And I should see 10 ".btn-primary" element
        And I should see 10 ".btn-secondary" element
        And I should see 5 ".btn-warning" element

    @javascript
    Scenario: I can paginate to page 2 of the complaints table
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        When I click the "a[data-dt-idx='1']" element
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
        And I should see 10 ".btn-danger" element
        And I should see 10 ".btn-success" element
        And I should see 5 ".btn-warning" element

    @javascript
    Scenario: I can sort the columns of the complaints table
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        When I click the "th:nth-of-type(10)" element
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
        And I should see 10 ".btn-secondary" element
        And I should see 10 ".btn-info" element
        And I should see 5 ".btn-dark" element

    @javascript
    Scenario: As an authenticated agent, with no complaints assigned to me, I should see an empty table
        Given I am authenticated with PR5KTZ9C from GN
        And I am on the homepage
        Then I should see 2 "tr" element
        And I should see "Aucune donnée disponible dans le tableau"

    @javascript
    Scenario: As an authenticated agent, with complaints assigned to me, I should see my complaints
        Given I am authenticated with H3U3XCGD from PN
        And I am on the homepage
        Then I should see 21 "tr" element
        And I should see 10 "th" element
        And I should not see "PEL-2023-00000001"
        And I should not see "PEL-2023-00000003"
        And I should not see "PEL-2023-00000005"
        And I should not see "PEL-2023-00000008"
        And I should not see "PEL-2023-00000010"
        And I should see "PEL-2023-00000011"
        And I should see "PEL-2023-00000015"
        And I should see "PEL-2023-00000018"
        And I should see "PEL-2023-00000021"
        And I should see "PEL-2023-00000025"
        And I should see "PEL-2023-00000027"
        And I should see "PEL-2023-00000030"
        And I should see the key "pel.to.process" translated
        And I should see 10 ".btn-primary" element
        And I should see 10 ".btn-warning" element

    @javascript
    Scenario: As an authenticated supervisor, I should see all complaints
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        Then I should see 26 "tr" element
        And I should see 11 "th" element
        And I should see "PEL-2023-00000001"
        And I should see "PEL-2023-00000003"
        And I should see "PEL-2023-00000005"
        And I should see "PEL-2023-00000008"
        And I should see "PEL-2023-00000010"
        And I should see "PEL-2023-00000011"
        And I should see "PEL-2023-00000018"
        And I should see "PEL-2023-00000022"
        And I should see "PEL-2023-00000025"

    @javascript
    Scenario: I can search complaints
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        When I fill in "search_query" with "Leo bernard"
        And I press "Rechercher"
        Then I should see 11 "tr" element
        When I fill in "search_query" with "4"
        And I press "Rechercher"
        Then I should see 17 "tr" element
        When I fill in "search_query" with "34"
        And I press "Rechercher"
        Then I should see 2 "tr" element
        When I fill in "search_query" with "44"
        And I press "Rechercher"
        Then I should see 2 "tr" element
        When I fill in "search_query" with "jean"
        And I press "Rechercher"
        Then I should see 26 "tr" element
        When I fill in "search_query" with "DUPONT"
        And I press "Rechercher"
        Then I should see 26 "tr" element
