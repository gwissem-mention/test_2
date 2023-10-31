Feature:
    In order to show homepage
    As a user
    I need to see a page with 5 buttons and 2 information texts

    @func
    Scenario: Show homepage on / route with 200 status code and a body
        Given I am on "/"
        Then the response status code should be 200
        And I should see "Accueil - Plainte en ligne" in the "title" element
        And I should see 1 "body" element

    @func
    Scenario Outline: Show homepage with all translated elements
        Given I am on "/"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                                     |
            | pel.complaint.online.welcome                              |
            | pel.ministry                                              |
            | pel.inside                                                |
            | pel.and.overseas                                          |
            | pel.header.baseline                                       |
            | pel.online.complaint                                      |
            | pel.portal                                                |
            | pel.home.title                                            |
            | pel.home.intro                                            |
            | pel.in.case.of.emergency.compose.the.17.or.the.112.title  |
            | pel.in.case.of.emergency.compose.the.17.or.the.112.text.1 |
            | pel.in.case.of.emergency.compose.the.17.or.the.112.text.2 |
            | pel.use.online.complaint.for                              |
            | pel.victim.of.damage.to.property                          |
            | pel.theft.victim                                          |
            | pel.victim.of.credit.card.theft                           |
            | pel.victim.of.offline.scams                               |
            | pel.victim.of.other.property.crimes                       |
            | pel.fill.a.complaint                                      |
            | pel.fsi.general.orienteer                                 |
            | pel.check.our.faq                                         |

    @func
    Scenario: Press button to be fsi general orienteer
        Given I am on "/"
        When I follow "Je suis dans une autre situation"

    @func
    Scenario: I can click on the footer links and see the correct pages
        Given I am on "/"
        When I follow "Plan du site"
        Then I should be on "/infos/plan-du-site"
        And the response status code should be 200
        And I should see the key "pel.footer.link-bottom.sitemap" translated
        When I follow "Accessibilité"
        Then I should be on "/infos/accessibilite"
        And the response status code should be 200
        And I should see the key "pel.footer.link-bottom.accessibility" translated
        When I follow "Mentions légales"
        Then I should be on "/infos/mentions-legales"
        And the response status code should be 200
        And I should see the key "pel.footer.link-bottom.legal" translated
        When I follow "Données personnelles"
        Then I should be on "/infos/donnees-personnelles"
        And the response status code should be 200
        And I should see the key "pel.footer.link-bottom.privacy" translated
        When I follow "Gestion des cookies"
        Then I should be on "/infos/gestion-des-cookies"
        And the response status code should be 200
        And I should see the key "pel.footer.link-bottom.cookies" translated

    @javascript
    Scenario: I can access to the FAQ page from the header
        Given I am on "/"
        When I follow "Centre d’aide"
        Then The page should open in a new tab and I switch to it
        And I should be on "/faq#pel-faq-section"
        And I close the current window

    @javascript
    Scenario: When I follow the link "J'ai un doute sur mon cas", I should see the FAQ page on a new tab
        Given I am on "/"
        When I follow "J’ai un doute sur mon cas"
        Then The page should open in a new tab and I switch to it
        And I should be on "/faq#pel-help-faq-concerned-with"
        And I close the current window
