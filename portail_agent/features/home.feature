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
        And I should see the key "pel.complaint.online.portal" translated
        And I should see "Brigade de proximité de Voiron"
        And I should see the key "pel.dashboard" translated
        And I should see the key "pel.the.declarations" translated
        And I should see the key "pel.faq.space" translated

    @func
    Scenario: I can navigate from the homepage to the homepage
        When I am on the homepage
        And I follow "Accueil"
        Then I am on "/"
        And I should see the key "pel.the.declarations" translated

    # Temporarily disabled
    #@func
    #Scenario: I can navigate from the homepage to the reporting page
    #    When I am on the homepage
    #    And I follow "Reporting"
    #    Then I am on "/reporting"
    #    And I should see the key "pel.reporting" translated

    @func
    Scenario: I can navigate from the homepage to the FAQ page
        When I am on the homepage
        And I follow "FAQ"
        Then I am on "/faq"
        And I should see the key "pel.faq" translated

    @javascript
    Scenario: I can click on a complaint link on the table
        Given I am on the homepage
        When I follow "PEL-2023-00000091"
        And I go to the tab 2
        Then I should be on "/plainte/recapitulatif/91"

    @javascript
    Scenario: I can click on a assignment notif and I should be redirected on the complaint and the notif should be removed
        Given I am authenticated with H3U3XCGD from PN
        And I am on the homepage
        When I click the "#notifications-dropdown" element
        Then I should see "La déclaration PEL-2023-00000011 vient de vous être attribuée"
        When I follow "La déclaration PEL-2023-00000011 vient de vous être attribuée"
        Then I should be on "/plainte/recapitulatif/11"
        When I click the "#notifications-dropdown" element
        Then I should not see "La déclaration PEL-2023-00000011 vient de vous être attribuée"

    @javascript
    Scenario: I should see a table datatables of 26 entries
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        Then I should see a "table#datatable" element
        And I should see 26 "table#datatable tr" element
        And I should see the key "pel.deposit.date" translated
        And I should see the key "pel.facts" translated
        And I should see the key "pel.alert" translated
        And I should see the key "pel.meeting.date" translated
        And I should see the key "pel.firstname.lastname" translated
        And I should see the key "pel.status" translated
        And I should see the key "pel.a.opj.name" translated
        And I should see the key "pel.declaration.number" translated
        And I should see the key "pel.comments" translated
        And I should see 20 "table#datatable .background-blue" element
        And I should see 5 "table#datatable .background-yellow" element

    @javascript
    Scenario: I can paginate to page 2 of the complaints table
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        When I click the "a[data-dt-idx='1']" element
        And I should see 26 "table#datatable tr" element
        And I should see the key "pel.deposit.date" translated
        And I should see the key "pel.facts" translated
        And I should see the key "pel.alert" translated
        And I should see the key "pel.meeting.date" translated
        And I should see the key "pel.firstname.lastname" translated
        And I should see the key "pel.status" translated
        And I should see the key "pel.a.opj.name" translated
        And I should see the key "pel.declaration.number" translated
        And I should see the key "pel.comments" translated
        And I should see 15 "table#datatable .background-yellow" element
        And I should see 35 "table#datatable .background-red" element

    @javascript
    Scenario: I can sort the columns of the complaints table
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        When I click the "th:nth-of-type(10)" element
        And I should see 26 "table#datatable tr" element
        And I should see the key "pel.deposit.date" translated
        And I should see the key "pel.facts" translated
        And I should see the key "pel.alert" translated
        And I should see the key "pel.meeting.date" translated
        And I should see the key "pel.firstname.lastname" translated
        And I should see the key "pel.status" translated
        And I should see the key "pel.a.opj.name" translated
        And I should see the key "pel.declaration.number" translated
        And I should see the key "pel.comments" translated
        And I should see 15 "table#datatable .background-blue" element
        And I should see 10 "table#datatable .background-cumulus" element

    @javascript
    Scenario: As an authenticated agent, with no complaints assigned to me, I should see an empty table
        Given I am authenticated with PR5KTZ9C from GN
        And I am on the homepage
        Then I should see 2 "table#datatable tr" element
        And I should see "Aucune donnée disponible dans le tableau"

    @javascript
    Scenario: As a guest, I should see a specific title in the header
        When I am on the homepage
        Then I should see the key "pel.complaint.online" translated
        And I should see the key "pel.portal" translated

    @javascript
    Scenario: As an authenticated agent, I should see a specific title in the header
        Given I am authenticated with H3U3XCGD from PN
        When I am on the homepage
        Then I should see the key "pel.complaint.online" translated
        And I should see the key "pel.portal" translated
        And I should see the key "pel.agent" translated
        And I should not see the key "pel.supervisor" translated

    @javascript
    Scenario: As an authenticated supervisor, I should see a specific title in the header
        Given I am authenticated with H3U3XCGF from PN
        When I am on the homepage
        Then I should see the key "pel.complaint.online" translated
        And I should see the key "pel.portal" translated
        And I should not see the key "pel.agent" translated
        And I should see the key "pel.supervisor" translated

    @javascript
    Scenario: As an authenticated agent, with complaints assigned to me, I should see my complaints
        Given I am authenticated with H3U3XCGD from PN
        And I am on the homepage
        Then I should see 21 "table#datatable tr" element
        And I should see 9 "table#datatable th" element
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
        And I should see 10 "table#datatable .background-blue" element
        And I should see 10 "table#datatable .background-yellow" element

    @javascript
    Scenario: As an authenticated supervisor, I should see all complaints
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        Then I should see 26 "table#datatable tr" element
        And I should see 10 "table#datatable th" element
        And I should see "PEL-2023-00000001"
        And I should see "PEL-2023-00000003"
        And I should see "PEL-2023-00000005"
        And I should see "PEL-2023-00000008"
        And I should see "PEL-2023-00000010"
        And I should see "PEL-2023-00000011"
        And I should see "PEL-2023-00000014"

    @javascript
    Scenario: I can search complaints
        Given I am authenticated with H3U3XCGF from PN
        And I am on the homepage
        When I fill in "search_query" with "Leo bernard"
        And I press "Rechercher"
        Then I should see 11 "table#datatable tr" element
        When I fill in "search_query" with "4"
        And I press "Rechercher"
        Then I should see 19 "table#datatable tr" element
        When I fill in "search_query" with "34"
        And I press "Rechercher"
        Then I should see 2 "table#datatable tr" element
        When I fill in "search_query" with "44"
        And I press "Rechercher"
        Then I should see 2 "table#datatable tr" element
        When I fill in "search_query" with "jean"
        And I press "Rechercher"
        Then I should see 26 "table#datatable tr" element
        When I fill in "search_query" with "DUPONT"
        And I press "Rechercher"
        Then I should see 26 "table#datatable tr" element

    @javascript
    Scenario: I can apply a filter on the complaints table
        Given I am on the homepage
        And I should see 26 "table#datatable tr" element
        Then I press "Filtres"
        When I click the "#reaches-deadline-filter" element
        Then I should see 2 "table#datatable tr" element
        And I should see "Aucune donnée disponible dans le tableau"
        When I click the "#exceeds-deadline-filter" element
        Then I should see 26 "table#datatable tr" element
        And I should see 20 ".background-blue" element
        And I should see 5 ".background-yellow" element
        When I click the "#reaches-deadline-filter" element
        Then I should see 2 "table#datatable tr" element
        And I should see "Aucune donnée disponible dans le tableau"
        When I click the "#alert-filter" element
        Then I should see 26 "table#datatable tr" element
        And I should see 20 ".background-blue" element
        And I should see 5 ".background-yellow" element
        When I click the "#assignment-pending-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 10 ".background-blue" element
        When I click the "#assigned-filter" element
        Then I should see 21 "table#datatable tr" element
        And I should see 20 ".background-blue" element
        When I click the "#validation-declaration-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 10 ".background-yellow" element
        When I click the "#appointment-planned-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 10 ".background-blue" element
        And I should see "01/12/2022"
        When I click the "#appointment-pending-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 10 ".background-cumulus" element
        When I click the "#unit-redirection-pending-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 10 ".background-cumulus" element
        When I click the "#closed-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 10 ".background-green" element
        When I click the "#waiting-closing-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 10 ".background-blue" element
        When I click the "#rejected-filter" element
        Then I should see 11 "table#datatable tr" element
        And I should see 20 ".background-red" element
