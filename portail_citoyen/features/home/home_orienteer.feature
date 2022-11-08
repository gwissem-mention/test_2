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
            | trans                                               |
            | pel.ministry                                        |
            | pel.inside                                          |
            | pel.and.overseas                                    |
            | pel.header.baseline                                 |
            | pel.online.complaint                                |
            | pel.my.route.to.fill.a.complaint                    |
            | pel.you.will.start.an.online.complaint.procedure    |
            | pel.appointment.needed.if                           |
            | pel.your.are.not.logged.in.with.france.connect      |
            | pel.your.are.victim.of.a.registered.vehicle.theft   |
            | pel.your.are.victim.of.a.violent.theft              |
            | pel.other.complex.offenses                          |
            | pel.if.no.lawyer.needed.text                        |
            | pel.online.complaint.process                        |
            | pel.1.fill.your.form                                |
            | pel.2.upload.your.files                             |
            | pel.3.your.request.is.sent.to.an.agent              |
            | pel.4.your.request.is.processed.within.48h          |
            | pel.i.take.note.of.legal.provisions                 |
            | pel.article.10.2                                    |
            | pel.of.the.penal.procedure                          |
            | pel.article.d.8.2.2                                 |
            | pel.back                                            |
            | pel.start.my.declaration                            |

    @func
    Scenario: Click on "Article 10-2" link
        Given I am on "/accueil-deroule"
        Then I follow "Article 10-2"
        And I should be on "https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000044569870"

    @func
    Scenario: Click on "Article D8-2-2" link
        Given I am on "/accueil-deroule"
        Then I follow "Article D8-2-2"
        And I should be on "https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000038552381"

    @func
    Scenario: Click on "Back" link
        Given I am on "/accueil-deroule"
        Then I follow "Retour"
        And I should be on "/"

    @func
    Scenario: Click on "Start" link
        Given I am on "/accueil-deroule"
        Then I follow "Débuter ma déclaration"
        And I should be on "/authentification"
