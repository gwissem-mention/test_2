Feature:
    In order to show homepage
    As a user
    I need to see a page with 5 buttons and 2 information texts

    @func
    Scenario: Show homepage on / route with 200 status code and header translated
        Given I am on "/"
        Then the response status code should be 200
        And I should see the key "pel.ministry" translated
        And I should see the key "pel.inside" translated
        And I should see the key "pel.and.overseas" translated
        And I should see 1 "body" element
        And I should see the key "pel.home.information.message.1" translated
        And I should see the key "pel.major" translated
        And I should see the key "pel.home.information.message.2" translated
        And I should see the key "pel.home.information.message.3" translated
        And I should see the key "pel.home.information.message.4" translated
        And I should see the key "pel.home.information.message.5" translated
        And I should see 7 "a" elements
        And I should see 4 ".fr-btn" elements
        And I should see the key "pel.home.emergency.message" translated
        And I should see the key "pel.file.a.complaint" translated
        And I should see the key "pel.faq" translated
        And I should see the key "pel.fsi.general.orienteer" translated
        And I should see the key "pel.home.fsi.general.orienteer.message" translated
    @func
    Scenario: Press button to be redirect to the visitor agreement page
        Given I am on "/"
        Then I follow "Continuer"
        And I should be on "/poursuivre"

    @func
    Scenario: Press button to be fsi general orienteer
        Given I am on "/"
        When I follow "Orienteur général FSI"
    @func
    Scenario: Press button to be redirect to perceval
        Given I am on "/"
        Then I follow "Être réorienté vers PERCEVAL"
        And I should be on "https://www.service-public.fr/particuliers/vosdroits/R46526"

    @func
    Scenario: Press button to be redirect to thésee
        Given I am on "/"
        Then I follow "Être réorienté vers THÉSÉE"
        And I should be on "https://www.service-public.fr/particuliers/vosdroits/N31138"
