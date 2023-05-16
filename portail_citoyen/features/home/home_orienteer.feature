Feature:
    In order to show the orienteer homepage
    As a user
    I need to see a page with working buttons and translated texts

    @func
    Scenario Outline: Show homepage with all translated elements
        Given I am on "/accueil-deroule"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "<trans>" translated

        Examples:
            | trans                                             |
            | pel.ministry                                      |
            | pel.inside                                        |
            | pel.and.overseas                                  |
            | pel.header.baseline                               |
            | pel.online.complaint                              |
            | pel.online.complaint.process                      |
            | pel.1.fill.your.form                              |
            | pel.2.your.request.is.sent.to.an.agent            |
            | pel.3.your.request.is.processed.within.48h        |
            | pel.complaint.create                              |

    @func
    Scenario: Click on "Back" link
        Given I am on "/accueil-deroule"
        Then I follow "retour vers les critères d’éligibilité pour la démarche"
        And I should be on "/accueil-confirmation#pel-confirm-online-complaint-that"

    @func
    Scenario: Click on "Start" link
        Given I am on "/accueil-deroule"
        Then I follow "Déposer plainte"
        And I should be on "/authentification#pel-to-log-in"
