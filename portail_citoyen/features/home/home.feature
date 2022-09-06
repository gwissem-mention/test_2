Feature:
    In order to show homepage
    As a user
    I need to see a page with 4 buttons and 2 information texts

    @func
    Scenario: Show homepage on / route with 200 status code
        Given I am on "/"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "information.message" translated
        And I should see 3 "a" elements
        And I should see 3 ".fr-btn" elements
        And I should see the key "home.emergency.message" translated

    @func
    Scenario: Press button to be redirect to the police
        Given I am on "/"
        Then I follow "Continuer"
        And I should be on "/visitor-agreement"

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
