Feature:
    In order to show homepage_confirmation
    As a user
    I need to see a page with 5 buttons and 2 information texts

    @func
    Scenario: Show homepage_confirmation on /accueil-confirmation route with 200 status code and a body
        Given I am on "/accueil-confirmation"
        Then the response status code should be 200
        And I should see 1 "body" element

    @func
    Scenario Outline: Show homepage with all translated elements
        Given I am on "/accueil-confirmation"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                                     |
            | pel.complaint.online.confirmation                         |
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
            | pel.confirm.online.complaint.that                         |
            | pel.you.dont.know.offense.author                          |
            | pel.you.are.major                                         |
            | pel.you.are.victim.or.representative                      |
            | pel.i.confirm                                             |
            | pel.fsi.general.orienteer.criteria                        |

    @func
    Scenario: Press button to be fsi general orienteer
        Given I am on "/accueil-confirmation"
        When I follow "Je ne remplis pas ces critères"
