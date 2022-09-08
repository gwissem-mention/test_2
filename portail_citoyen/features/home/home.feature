Feature:
    In order to show homepage
    As a user
    I need to see a page with 5 buttons and 2 information texts

    @func
    Scenario: Show homepage on / route with 200 status code and header translated
        Given I am on "/"
        Then the response status code should be 200
        And I should see the key "ministry" translated
        And I should see the key "inside" translated
        And I should see the key "and.overseas" translated
        And I should see 1 "body" element
        And I should see the key "home.information.message.1" translated
        And I should see the key "major" translated
        And I should see the key "home.information.message.2" translated
        And I should see the key "home.information.message.3" translated
        And I should see the key "home.information.message.4" translated
        And I should see the key "home.information.message.5" translated
        And I should see 6 "a" elements
        And I should see 5 ".fr-btn" elements
        And I should see the key "home.emergency.message" translated

    @func
    Scenario: Press button to be redirect to the visitor agreement page
        Given I am on "/"
        Then I follow "Continuer"
        And I should be on "/poursuivre"

    @func
    Scenario: Press button to be redirect to the police
        Given I am on "/"
        Then I follow "Être redirigé vers l'orienteur de la Police Nationale"
        And I should be on "https://www.moncommissariat.interieur.gouv.fr"

    @func
    Scenario: Press button to be redirect to the gendarmerie
        Given I am on "/"
        Then I follow "Être redirigé vers l'orienteur de la Gendarmerie Nationale"
        And I should be on "https://www.gendarmerie.interieur.gouv.fr/"
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
