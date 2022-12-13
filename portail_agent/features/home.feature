Feature:
    In order to show the homepage
    As a user
    I want to see the homepage

    @func
    Scenario: I can navigate on the homepage and I should see a header and an image
        When I am on the homepage
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.search" translated
        And the response should contain the key "pel.home" translated
        And I should see the key "pel.agent.complaint.online" translated
        And I should see the key "pel.header.baseline" translated
        And I should see the key "pel.complaint.online.portal" translated
        And I should see the key "pel.fixtures.fsi.type" translated
        And I should see the key "pel.fixtures.fsi.town" translated
        And I should see the key "pel.home" translated
        And the response should contain the key "pel.home.title" translated
        And I should see the key "pel.reporting" translated
        And the response should contain the key "pel.reporting.title" translated
        And I should see the key "pel.faq" translated
        And the response should contain the key "pel.faq.title" translated

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
